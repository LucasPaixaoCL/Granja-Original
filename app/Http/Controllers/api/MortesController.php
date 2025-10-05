<?php

namespace App\Http\Controllers\Api;

use App\Models\Lote;
use App\Models\Morte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Api\MainController;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class MortesController extends Controller
{
    public function graficoMortes()
    {
        $lotes = Lote::with('mortes')->orderBy('data_morte', 'desc')->get();

        $labels = [];
        $data = [];

        $totalMortes = 0;

        // Calcular total de mortes
        foreach ($lotes as $lote) {
            $totalMortes += $lote->mortes->sum('qtde_mortes');
        }

        // Preparar dados para o gráfico
        foreach ($lotes as $lote) {
            $qtde = $lote->mortes->sum('qtde_mortes');
            if ($qtde > 0) {
                $labels[] = 'Lote ' . $lote->num_lote;
                $data[] = round(($qtde / $totalMortes) * 100, 2);
            }
        }

        return view('dashboard.grafico-mortes', compact('labels', 'data'));
    }

    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'mortes' => Morte::with(['lote'])->select('*')->orderBy('semana', 'desc')->orderBy('data_morte', 'desc')->get(),
            'lotes'  => Lote::select('*')->get(),
        ];

        return view('mortes.listar', compact('dados'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'lotes' => Lote::all()
        ];

        return view('mortes.adicionar', compact('dados'));
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'lote' => 'required|exists:lotes,id',
            'data_morte' => [
                'required',
                Rule::unique('mortes', 'data_morte')->where(function ($query) use ($request) {
                    return $query->where('lote_id', $request->lote);
                })
            ],
            'qtde_mortes' => 'required|numeric|min:1'
        ]);

        $main = new MainController();

        $morte = new Morte();
        $morte->lote_id = $request->lote;

        $lote = Lote::find($request->lote);
        $data_inicial = $lote->data_lote;
        $data_final = $request->data_morte;

        $morte->semana = $main->calcularSemana($data_inicial, $data_final) + 1;

        $morte->data_morte = $request->data_morte;
        $morte->qtde_mortes = $request->qtde_mortes;

        $morte->save();

        return redirect()->route('mortes.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'morte' => Morte::findOrFail(Crypt::decryptString($id))
        ];

        return view('mortes.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'morte' => Morte::findOrFail($id)
        ];

        return view('mortes.editar', compact('dados'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        // $request->validate([
        //     'nome' => 'required|min:3|max:30'
        // ]);

        $morte = Morte::findOrFail($request->id);

        $morte->update([
            'nome' => $request->nome
        ]);

        return redirect()->route('mortes.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'morte' => Morte::findOrFail(Crypt::decryptString($id))
        ];

        return view('mortes.confirmar', compact('dados'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $morte = Morte::findOrFail(Crypt::decryptString($id));
        $morte->delete();
        return redirect()->route('mortes.index');
    }
}
