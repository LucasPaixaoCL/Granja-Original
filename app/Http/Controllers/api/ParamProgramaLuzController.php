<?php

namespace App\Http\Controllers\Api;

use App\Models\ParamProgramaLuz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
class ParamProgramaLuzController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $results = ParamProgramaLuz::all();
        return view('parametros.programa_luz.listar', compact('results'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        return view('parametros.programa_luz.adicionar');
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'nome' => 'required|min:3|max:30'
        ]);

        $vacina = new ParamProgramaLuz();
        $vacina->save();

        return redirect()->route('parametros.programa_luz.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $vacina = ParamProgramaLuz::findOrFail(Crypt::decryptString($id));
        return view('parametros.programa_luz.detalhes', compact('vacina'));    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $vacina = ParamProgramaLuz::findOrFail(Crypt::decryptString($id));
        return view('parametros.programa_luz.editar', compact('vacina'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'nome' => 'required|min:3|max:30'
        ]);

        $vacina = ParamProgramaLuz::findOrFail($request->id);

        $vacina->update([
            'nome' => $request->nome
        ]);

        return redirect()->route('parametros.programa_luz.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $vacina = ParamProgramaLuz::findOrFail(Crypt::decryptString($id));
        return view('parametros.programa_luz.confirm', compact('vacina'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $vacina = ParamProgramaLuz::findOrFail(Crypt::decryptString($id));
        $vacina->delete();
        return redirect()->route('parametros.programa_luz.index');
    }
}
