<?php

namespace App\Http\Controllers\Api;

use App\Models\ParamCategoriaDespesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class ParamCategoriaDespesaController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $categorias_despesa = ParamCategoriaDespesa::all();
        return view('parametros.categoria_despesa.listar', compact('categorias_despesa'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        return view('parametros.categoria_despesa.adicionar');
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $categoria_despesa = new ParamCategoriaDespesa();
        $categoria_despesa->descricao = $request->descricao;
        $categoria_despesa->save();

        return redirect()->route('param.categoria.despesa.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $categoria_despesa = ParamCategoriaDespesa::findOrFail(Crypt::decryptString($id));
        return view('parametros.categoria_despesa.detalhes', compact('categoria_despesa'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $categoria_despesa = ParamCategoriaDespesa::findOrFail(Crypt::decryptString($id));
        return view('parametros.categoria_despesa.editar', compact('categoria_despesa'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $categoria_despesa = ParamCategoriaDespesa::findOrFail($request->id);

        $categoria_despesa->update([
            'descricao' => $request->descricao
        ]);

        return redirect()->route('param.categoria.despesa.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $categoria_despesa = ParamCategoriaDespesa::findOrFail(Crypt::decryptString($id));
        return view('parametros.categoria_despesa.confirmar', compact('categoria_despesa'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $categoria_despesa = ParamCategoriaDespesa::findOrFail(Crypt::decryptString($id));
        $categoria_despesa->delete();
        return redirect()->route('param.categoria.despesa.index');
    }
}
