<?php

namespace App\Http\Controllers\Api;

use App\Models\Lote;
use App\Models\Nucleo;
use App\Models\ParamLinhagem;
use App\Models\ParamProgramaVacinacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Api\MainController;
use App\Models\Morte;
use App\Http\Controllers\Controller;

class LotesController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $lotes = Lote::with(['mortes', 'nucleo', 'galpoes'])->get();

        $totais = Lote::selectRaw('SUM(qtde_aves) as total_aves, SUM(qtde_machos) as total_machos')->first();
        $total_mortes = Morte::sum('qtde_mortes');

        $dados = [
            'lotes' => $lotes,
            'total_aves' => $totais->total_aves,
            'total_machos' => $totais->total_machos,
            'total_mortes' => $total_mortes
        ];

        return view('lotes.listar', compact('dados'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'programa_vacinacao' => ParamProgramaVacinacao::select('id', 'descricao')->get(),
            'linhagens' => ParamLinhagem::select('id', 'descricao')->get(),
            'nucleos' => Nucleo::select('id', 'descricao')->get()
        ];

        return view('lotes.adicionar', compact('dados'));
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        // $request->validate([
        //     'nome' => 'required|min:3|max:30'
        // ]);

        $lote = new Lote();
        $lote->nucleo_id = $request->nucleo;
        $lote->num_lote = $this->buscaMaiorLote($request->nucleo);
        $lote->data_lote = $request->data_lote;
        $lote->qtde_aves = $request->qtde_aves;
        $lote->qtde_machos = $request->qtde_machos;
        $lote->save();

        return redirect()->route('lotes.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $lote = Lote::with('mortes', 'nucleo', 'coletas')->findOrFail(Crypt::decryptString($id));

        $main = new MainController();

        $dados = [
            'lote'  => $lote,
            'semana' => $main->calcularSemana($lote->data_lote, now())
        ];

        return view('lotes.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $lote = Lote::findOrFail(Crypt::decryptString($id));
        return view('lotes.editar', compact('lote'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $validated = $request->validate([
            'id' => 'required|exists:lotes,id',
            'nome' => 'required|string|min:3|max:30'
        ]);

        $lote = Lote::select('id')->findOrFail($validated['id']); 

        $lote->update([
            'nome' => $validated['nome']
        ]);

        return redirect()->route('lotes.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $decryptedId = Crypt::decryptString($id);

        $dados = [
            'lote' => Lote::select('id', 'qtde_aves', 'qtde_machos', 'data_entrada') 
                ->findOrFail($decryptedId)
        ];

        return view('lotes.confirmar', compact('dados'));
    }

    public function destroy($id)
    {

        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $lote = Lote::findOrFail(Crypt::decryptString($id));

        if ($lote->mortes()->exists()) {
            return back()->with('error', 'Não é possível excluir este lote pois há mortes associadas a ele.');
        }

        if ($lote->coletas()->exists()) {
            return back()->with('error', 'Não é possível excluir este lote pois há coletas associadas a ele.');
        }

        $lote->delete();

        return redirect()->route('lotes.index');
    }

    public function buscaMaiorLote($nucleo_id)
    {
        return Lote::where('nucleo_id', $nucleo_id)->max('num_lote') + 1;
    }
}
