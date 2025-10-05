<?php

namespace App\Http\Controllers\Api;

use App\Models\Cliente;
use App\Models\FormaPgto;
use App\Models\Formato;
use App\Models\Funcionario;
use App\Models\ParamSituacaoPagamento;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
class VendasController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'vendas' => Venda::with('cliente', 'funcionario', 'formato', 'forma_pgto', 'situacao')->orderBy('data_venda', 'desc')->get()
        ];

        //dd($dados);

        return view('vendas.listar', compact('dados'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'clientes' => Cliente::orderBy('nome')->get(),
            'vendedores' => Funcionario::orderBy('nome')->get(),
            'formas_pgto' => FormaPgto::all(),
            'formatos' => Formato::all(),
            'situacoes' => ParamSituacaoPagamento::all()
        ];

        return view('vendas.adicionar', compact('dados'));
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        // $request->validate([
        //     'data_venda' => 'required'
        // ]);

        $venda = new Venda();
        $venda->data_venda = $request->data_venda;

        $venda->formato_id = $request->formato;
        $venda->tipo = empty($request->tipo) ? 30 : $request->tipo;
        $venda->tamanho = 'G'; // alterar
        $venda->qtde = $request->quantidade;
        $venda->valor_unit = $request->valor_unitario;
        $venda->desconto = $request->desconto;
        $quantidade = (float) $request->quantidade;
        $valor_unitario = (float) $request->valor_unitario;
        $desconto = (float) $request->desconto;

        $subtotal = $quantidade * $valor_unitario - $desconto;

        if ((float)$subtotal != (float)$request->subtotal) {
            return back()->with('error', 'O subtotal informado não confere com o valor calculado.');
        }

        $venda->subtotal = $subtotal;
        $venda->cliente_id = $request->cliente;
        $venda->funcionario_id = $request->vendedor;

        $situacao = $request->situacao;

        if ((int) $situacao === 0) {
            $situacao = 1;
        }

        $venda->situacao_id = $situacao;

        $venda->forma_pgto_id = $request->forma_pgto;

        $ovos_vendidos = 0;

        if ((int) $request->formato == 1) { // 1 = caixa e 2 = cartela
            $ovos_vendidos = 360 * (int)$request->quantidade;
        } else {
            $ovos_vendidos = 30 * (int)$request->quantidade; // 30 pode variar 
        }

        $venda->qtde_ovos = $ovos_vendidos;

        $venda->save();

        return redirect()->route('vendas.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'venda' => Venda::with('cliente', 'funcionario', 'forma_pgto')->findOrFail(Crypt::decryptString($id))
        ];

        return view('vendas.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $venda = Venda::findOrFail(Crypt::decryptString($id));
        return view('vendas.editar', compact('venda'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'nome' => 'required|min:3|max:30'
        ]);

        $venda = Venda::findOrFail($request->id);

        $venda->update([
            'nome' => $request->nome
        ]);

        return redirect()->route('vendas.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $venda = Venda::findOrFail(Crypt::decryptString($id));
        return view('vendas.confirmar', compact('venda'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $venda = Venda::findOrFail(Crypt::decryptString($id));
        $venda->delete();
        return redirect()->route('vendas.index');
    }
}
