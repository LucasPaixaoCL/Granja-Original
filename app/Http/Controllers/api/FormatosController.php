<?php

namespace App\Http\Controllers\Api;

use App\Models\Formato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class FormatosController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $formatos = Formato::orderBy('descricao', 'asc')->get();
        return view('formatos.listar', compact('formatos'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        return view('formatos.adicionar');
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $formato = new Formato();
        $formato->descricao = $request->descricao;
        $formato->save();

        return redirect()->route('formatos.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $formato = Formato::findOrFail(Crypt::decryptString($id));
        return view('formatos.detalhes', compact('formato'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $formato = Formato::findOrFail(Crypt::decryptString($id));
        return view('formatos.editar', compact('formato'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $formato = Formato::findOrFail($request->id);

        $formato->update([
            'descricao' => $request->nome
        ]);

        return redirect()->route('formatos.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $formato = Formato::findOrFail(Crypt::decryptString($id));
        return view('formatos.confirmar', compact('formato'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $formato = Formato::findOrFail(Crypt::decryptString($id));
        $formato->delete();
        return redirect()->route('formatos.index');
    }
}
