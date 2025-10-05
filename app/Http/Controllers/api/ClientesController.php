<?php

namespace App\Http\Controllers\Api;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class ClientesController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $clientes = Cliente::orderBy('nome')->get();
        return view('clientes.listar', compact('clientes'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $cliente = Cliente::all();
        return view('clientes.adicionar', compact('cliente'));
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'nome' => 'required|min:3|max:50'
        ]);

        // $file = $request->file('arquivo');

        // $nomeArquivo = Str::slug($request->nome) . '-' . time() . '.' . $file->getClientOriginalExtension();

        // $file->storeAs('arquivos', $nomeArquivo, 'public');

        $cliente = new Cliente();
        $cliente->nome = $request->nome;
        $cliente->email = $request->email;
        $cliente->telefone = $request->telefone;
        // $cliente->titulo = $request->titulo;
        // $cliente->arquivo = 'arquivos/' . $nomeArquivo;
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'cliente' => Cliente::findOrFail(Crypt::decryptString($id))
        ];

        return view('clientes.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $cliente = Cliente::findOrFail(Crypt::decryptString($id));
        return view('clientes.editar', compact('cliente'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'nome_cliente' => 'required|min:3|max:30'
        ]);

        $cliente = Cliente::findOrFail($request->id);

        $cliente->update([
            'nome' => $request->nome_cliente
        ]);

        return redirect()->route('clientes.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $cliente = Cliente::findOrFail(Crypt::decryptString($id));
        return view('clientes.confirmar', compact('cliente'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $cliente = Cliente::findOrFail(Crypt::decryptString($id));
        $cliente->delete();
        return redirect()->route('clientes.index');
    }
}
