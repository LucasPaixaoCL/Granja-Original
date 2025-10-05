<?php

namespace App\Http\Controllers\Api;

use App\Models\ParamLinhagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class ParamLinhagensController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $results = ParamLinhagem::all();
        return view('parametros.linhagens.listar', compact('results'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        return view('parametros.linhagens.adicionar');
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $result = new ParamLinhagem();
        $result->descricao = $request->descricao;
        $result->save();

        return redirect()->route('param.linhagens.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $nucleo = ParamLinhagem::findOrFail(Crypt::decryptString($id));
        return view('nucleos.detalhes', compact('nucleo'));    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $nucleo = ParamLinhagem::findOrFail(Crypt::decryptString($id));
        return view('nucleos.editar', compact('nucleo'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $result = ParamLinhagem::findOrFail($request->id);

        $result->update([
            'descricao' => $request->descricao
        ]);

        return redirect()->route('param.linhagens.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $result = ParamLinhagem::findOrFail(Crypt::decryptString($id));
        return view('parametros.linhagens.confirmar', compact('result'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $result = ParamLinhagem::findOrFail(Crypt::decryptString($id));
        $result->delete();
        return redirect()->route('param.linhagens.index');
    }
}
