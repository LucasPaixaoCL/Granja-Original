<?php

namespace App\Http\Controllers\Api;

use App\Models\ParamConsumoRacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class ParamConsumoRacaoController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $consumo_racao = ParamConsumoRacao::all();
        return view('parametros.consumo_racao.listar', compact('consumo_racao'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        return view('parametros.consumo_racao.adicionar');
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'semana' => 'required',
            'consumo_dia' => 'required',
            'consumo_semana' => 'required',
        ]);

        $consumo = new ParamConsumoRacao();
        $consumo->semana = $request->semana;
        $consumo->consumo_dia = $request->consumo_dia;
        $consumo->consumo_semana = $request->consumo_semana;
        $consumo->save();

        return redirect()->route('param.consumo.racao.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $consumo = ParamConsumoRacao::findOrFail(Crypt::decryptString($id));
        return view('parametros.consumo_racao.detalhes', compact('consumo'));    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $consumo = ParamConsumoRacao::findOrFail(Crypt::decryptString($id));
        return view('parametros.consumo_racao.editar', compact('consumo'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'semana' => 'required',
            'consumo_dia' => 'required',
            'consumo_semana' => 'required',
        ]);

        $consumo = ParamConsumoRacao::findOrFail($request->id);

        $consumo->update([
            'semana' => $request->semana,
            'consumo_dia' => $request->consumo_dia,
            'consumo_semana' => $request->consumo_semana
        ]);

        return redirect()->route('param.consumo.racao.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $consumo = ParamConsumoRacao::findOrFail(Crypt::decryptString($id));
        return view('parametros.consumo_racao.confirmar', compact('consumo'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $consumo = ParamConsumoRacao::findOrFail(Crypt::decryptString($id));
        $consumo->delete();
        return redirect()->route('param.consumo.racao.index');
    }
}
