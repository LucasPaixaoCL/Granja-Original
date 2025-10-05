<?php

namespace App\Http\Controllers\Api;

use App\Models\ParamProgramaVacinacao;
use App\Models\ParamViaAplicacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class ParamProgramaVacinacaoController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'programa_vacinacao' => ParamProgramaVacinacao::all(),
            'via_aplicacao' => $this->getParamViaAplicacao()
        ];

        return view('parametros.programa_vacinacao.listar', compact('dados'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'via_aplicacao' => $this->getParamViaAplicacao()
        ];

        return view('parametros.programa_vacinacao.adicionar', compact('dados'));
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30'
        ]);

        $plano_vacinacao = new ParamProgramaVacinacao();
        $plano_vacinacao->descricao = $request->descricao;
        $plano_vacinacao->save();

        return redirect()->route('param.programa.vacinacao.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'plano_vacinacao' => ParamProgramaVacinacao::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.programa_vacinacao.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'plano_vacinacao' => ParamProgramaVacinacao::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.programa_vacinacao.editar', compact('dados'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        // $request->validate([
        //     'nome' => 'required|min:3|max:30'
        // ]);

        $plano_vacinacao = ParamProgramaVacinacao::findOrFail($request->id);

        $plano_vacinacao->update([
            'descricao' => $request->descricao
        ]);

        return redirect()->route('param.programa.vacinacao.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'plano_vacinacao' => ParamProgramaVacinacao::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.programa_vacinacao.confirmar', compact('dados'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $plano_vacinacao = ParamProgramaVacinacao::findOrFail(Crypt::decryptString($id));
        $plano_vacinacao->delete();
        return redirect()->route('param.programa.vacinacao.index');
    }

    protected function getParamViaAplicacao()
    {
        return ParamViaAplicacao::orderBy('descricao')->get();
    }
}
