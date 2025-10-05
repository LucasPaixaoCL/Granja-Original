<?php

namespace App\Http\Controllers\Api;

use App\Models\Descarte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class DescartesController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'descartes' => Descarte::orderBy('data_descarte', 'desc')->get()
        ];

        return view('descartes.listar', compact('dados'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        return view('descartes.adicionar');
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'data_descarte' => 'required|date|unique:descartes,data_descarte',
            'qtde_ovos' => 'required|integer|min:1'
        ]);

        $descarte = new Descarte();
        $descarte->data_descarte = $request->data_descarte;
        $descarte->qtde_ovos = $request->qtde_ovos;
        $descarte->save();

        return redirect()->route('descartes.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'descarte' => Descarte::findOrFail(Crypt::decryptString($id))
        ];

        return view('descartes.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'descarte' => Descarte::findOrFail(Crypt::decryptString($id))
        ];

        return view('descartes.editar', compact('dados'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        // $request->validate([
        //     'nome' => 'required|min:3|max:30'
        // ]);

        $despesa = Descarte::findOrFail($request->id);

        $despesa->update([
            'nome' => $request->nome
        ]);

        return redirect()->route('descartes.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'descarte' => Descarte::findOrFail(Crypt::decryptString($id))
        ];

        return view('descartes.confirmar', compact('dados'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $descarte = Descarte::findOrFail(Crypt::decryptString($id));
        $descarte->delete();
        return redirect()->route('descartes.index');
    }
}
