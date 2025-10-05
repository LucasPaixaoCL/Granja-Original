<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
class SuporteController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        echo "index";
        die();
        return view('vendas.listar', compact('dados'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        echo "create";
        die();
        return view('vendas.adicionar', compact('dados'));
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        echo "store";
        die();
        return redirect()->route('vendas.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        echo "show";
        die();
        return view('vendas.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        echo "edit";
        die();
        return view('vendas.editar', compact('venda'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        echo "update";
        die();
        return redirect()->route('vendas.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        echo "confirm";
        die();
        return view('vendas.confirmar', compact('venda'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        echo "destroy";
        die();
        return redirect()->route('vendas.index');
    }
}
