<?php

namespace App\Http\Controllers\Api;

use App\Models\FormaPgto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class FormasPgtoController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $formas_pgto = FormaPgto::all();
        return view('formas_pgto.listar', compact('formas_pgto'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        return view('formas_pgto.adicionar');
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $forma_pgto = new FormaPgto();
        $forma_pgto->descricao = $request->descricao;
        $forma_pgto->save();

        return redirect()->route('formas_pgto.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $forma_pgto = FormaPgto::findOrFail(Crypt::decryptString($id));
        return view('formas_pgto.detalhes', compact('forma_pgto'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $forma_pgto = FormaPgto::findOrFail(Crypt::decryptString($id));
        return view('formas_pgto.editar', compact('forma_pgto'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $forma_pgto = FormaPgto::findOrFail($request->id);

        $forma_pgto->update([
            'descricao' => $request->nome
        ]);

        return redirect()->route('formas_pgto.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $forma_pgto = FormaPgto::findOrFail(Crypt::decryptString($id));
        return view('formas_pgto.confirmar', compact('forma_pgto'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $forma_pgto = FormaPgto::findOrFail(Crypt::decryptString($id));
        $forma_pgto->delete();
        return redirect()->route('formas_pgto.index');
    }
}
