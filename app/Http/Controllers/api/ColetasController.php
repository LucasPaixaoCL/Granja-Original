<?php

namespace App\Http\Controllers\Api;

use App\Models\ColetaOvo;
use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Controller;


class ColetasController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'coletas' => ColetaOvo::with('lote')->orderBy('data_coleta')->get()
        ];

        return view('coletas.listar', compact('dados'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'lotes' => Lote::all()
        ];

        return view('coletas.adicionar', compact('dados'));
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'lote' => 'required|not_in:0',
            'data_coleta' => 'required',
            'qtde_ovos' => 'required'
        ]);

        $main = new MainController();

        $coleta = new ColetaOvo();
        $coleta->lote_id = $request->lote;

        $lote = Lote::find($request->lote);
        $data_inicial = $lote->data_lote;
        $data_final = $request->data_coleta;

        $coleta->semana = $main->calcularSemana($data_inicial, $data_final);

        $coleta->data_coleta = $request->data_coleta;
        $coleta->qtde_ovos = $request->qtde_ovos;
        $coleta->save();

        return redirect()->route('coletas.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'coleta' => ColetaOvo::findOrFail(Crypt::decryptString($id))
        ];

        return view('coletas.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'coleta' => ColetaOvo::findOrFail(Crypt::decryptString($id))
        ];

        return view('coletas.editar', compact('dados'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'nome' => 'required|min:3|max:30'
        ]);

        $coleta = ColetaOvo::findOrFail($request->id);

        $coleta->update([
            'nome' => $request->nome
        ]);

        return redirect()->route('coletas.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'coleta' => ColetaOvo::findOrFail(Crypt::decryptString($id))
        ];

        return view('coletas.confirmar', compact('dados'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $coleta = ColetaOvo::findOrFail(Crypt::decryptString($id));
        $coleta->delete();
        return redirect()->route('coletas.index');
    }
}
