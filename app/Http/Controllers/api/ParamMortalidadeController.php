<?php

namespace App\Http\Controllers\Api;

use App\Models\ParamMortalidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class ParamMortalidadeController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $param_mortalidade = ParamMortalidade::all();
        return view('parametros.mortalidade.listar', compact('param_mortalidade'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        return view('parametros.mortalidade.adicionar');
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'semana' => 'required',
            'padrao' => 'required'
        ]);

        $param_mortalidade = new ParamMortalidade();
        $param_mortalidade->semana = $request->semana;
        $param_mortalidade->padrao = $request->padrao;
        $param_mortalidade->save();

        return redirect()->route('param.mortalidade.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $param_mortalidade = ParamMortalidade::findOrFail(Crypt::decryptString($id));
        return view('parametros.mortalidade.detalhes', compact('param_mortalidade'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $natureza_despeza = ParamMortalidade::findOrFail(Crypt::decryptString($id));
        return view('parametros.natureza_despesa.editar', compact('natureza_despeza'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $natureza_despeza = ParamMortalidade::findOrFail($request->id);

        $natureza_despeza->update([
            'descricao' => $request->descricao
        ]);

        return redirect()->route('param.natureza.despesa.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $param_mortalidade = ParamMortalidade::findOrFail(Crypt::decryptString($id));
        return view('parametros.mortalidade.confirmar', compact('param_mortalidade'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $param_mortalidade = ParamMortalidade::findOrFail(Crypt::decryptString($id));
        $param_mortalidade->delete();
        return redirect()->route('param.mortalidade.index');
    }
}
