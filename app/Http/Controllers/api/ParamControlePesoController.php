<?php

namespace App\Http\Controllers\Api;

use App\Models\ParamControlePeso;
use App\Models\ParamLinhagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class ParamControlePesoController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'controle_peso' => ParamControlePeso::all()
        ];
        return view('parametros.controle_peso.listar', compact('dados'));
        
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'linhagens' => ParamLinhagem::all()
        ];

        return view('parametros.controle_peso.adicionar', compact('dados'));
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'semana' => 'required',
            'peso_min' => 'required',
            'peso_max' => 'required'
        ]);

        $controle_peso = new ParamControlePeso();
        $controle_peso->semana = $request->semana;
        $controle_peso->peso_min = $request->peso_min;
        $controle_peso->peso_max = $request->peso_max;
        $controle_peso->save();

        return redirect()->route('param.controle.peso.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'controle_peso' => ParamControlePeso::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.controle_peso.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'controle_peso' => ParamControlePeso::findOrFail(Crypt::decryptString($id))
        ];

        return view('nucleos.editar', compact('dados'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $result = ParamControlePeso::findOrFail($request->id);

        $result->update([
            'descricao' => $request->descricao
        ]);

        return redirect()->route('param.controle.peso.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados  = [
            'controle_peso' => ParamControlePeso::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.controle_peso.confirmar', compact('dados'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $result = ParamControlePeso::findOrFail(Crypt::decryptString($id));
        $result->delete();
        return redirect()->route('param.controle.peso.index');
    }
}
