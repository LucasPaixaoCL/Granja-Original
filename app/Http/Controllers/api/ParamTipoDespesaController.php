<?php

namespace App\Http\Controllers\Api;

use App\Models\ParamTipoDespesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
class ParamTipoDespesaController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'tipos_despesa' => ParamTipoDespesa::all()
        ];

        return view('parametros.tipo_despesa.listar', compact('dados'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        return view('parametros.tipo_despesa.adicionar');
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $tipo_despesa = new ParamTipoDespesa();
        $tipo_despesa->descricao = $request->descricao;
        $tipo_despesa->save();

        return redirect()->route('param.tipo.despesa.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'tipo_despesa' => ParamTipoDespesa::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.tipo_despesa.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'tipo_despesa' => ParamTipoDespesa::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.tipo_despesa.editar', compact('dados'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $tipo_despesa = ParamTipoDespesa::findOrFail($request->id);

        $tipo_despesa->update([
            'descricao' => $request->descricao
        ]);

        return redirect()->route('para.tipo.despesa.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'tipo_despesa' => ParamTipoDespesa::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.tipo_despesa.confirmar', compact('dados'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $tipo_despesa = ParamTipoDespesa::findOrFail(Crypt::decryptString($id));
        $tipo_despesa->delete();
        return redirect()->route('param.tipo.despesa.index');
    }
}
