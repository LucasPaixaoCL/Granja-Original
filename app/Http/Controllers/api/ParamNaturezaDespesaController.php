<?php

namespace App\Http\Controllers\Api;

use App\Models\ParamNaturezaDespesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class ParamNaturezaDespesaController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'natureza_despeza' => ParamNaturezaDespesa::all()
        ];

        return view('parametros.natureza_despesa.listar', compact('dados'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        return view('parametros.natureza_despesa.adicionar');
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $natureza_despeza = new ParamNaturezaDespesa();
        $natureza_despeza->descricao = $request->descricao;
        $natureza_despeza->save();

        return redirect()->route('param.natureza.despesa.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'natureza_despeza' => ParamNaturezaDespesa::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.natureza_despesa.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'natureza_despeza' => ParamNaturezaDespesa::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.natureza_despesa.editar', compact('natureza_despeza'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $natureza_despeza = ParamNaturezaDespesa::findOrFail($request->id);

        $natureza_despeza->update([
            'descricao' => $request->descricao
        ]);

        return redirect()->route('param.natureza.despesa.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'natureza_despeza' => ParamNaturezaDespesa::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.natureza_despesa.confirmar', compact('dados'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $natureza_despeza = ParamNaturezaDespesa::findOrFail(Crypt::decryptString($id));
        $natureza_despeza->delete();
        return redirect()->route('param.natureza.despesa.index');
    }
}
