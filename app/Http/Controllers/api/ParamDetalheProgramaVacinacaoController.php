<?php

namespace App\Http\Controllers\Api;

use App\Models\ParamDetalheProgramaVacinacao;
use App\Models\ParamProgramaVacinacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\ParamViaAplicacao;
use App\Http\Controllers\Controller;

class ParamDetalheProgramaVacinacaoController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'detalhe_programa_vacinacao' => ParamDetalheProgramaVacinacao::with('programa', 'via_aplicacao')->get()
        ];

       // dd($dados);

        return view('parametros.detalhe_programa_vacinacao.listar', compact('dados'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'detalhe_programa_vacinacao' => ParamProgramaVacinacao::all(),
            'via_aplicacao' => $this->getParamViaAplicacao()
        ];

        return view('parametros.detalhe_programa_vacinacao.adicionar', compact('dados'));
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        // $request->validate([
        //     'dia' => 'required',
        //     'semana' => 'required',
        //     'enfermidade' => 'required'
        // ]);

        $plano_vacinacao = new ParamDetalheProgramaVacinacao();
        $plano_vacinacao->param_programa_vacinacao_id = $request->programa_vacinacao;
        $plano_vacinacao->dia = $request->dia;
        $plano_vacinacao->semana = $request->semana;
        $plano_vacinacao->enfermidade = $request->enfermidade;
        $plano_vacinacao->param_via_aplicacao_id = $request->via_aplicacao;
        $plano_vacinacao->save();

        return redirect()->route('param.detalhe.programa.vacinacao.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'detalhe_programa_vacinacao' => ParamDetalheProgramaVacinacao::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.detalhe_programa_vacinacao.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'detalhe_programa_vacinacao' => ParamDetalheProgramaVacinacao::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.detalhe_programa_vacinacao.editar', compact('dados'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        // $request->validate([
        //     'nome' => 'required|min:3|max:30'
        // ]);

        $detalhe_programa_vacinacao = ParamDetalheProgramaVacinacao::findOrFail($request->id);

        $detalhe_programa_vacinacao->update([
            'descricao' => $request->descricao
        ]);

        return redirect()->route('param.detalhe.programa.vacinacao.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'detalhe_programa_vacinacao' => ParamDetalheProgramaVacinacao::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.detalhe_programa_vacinacao.confirmar', compact('dados'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $detalhe_programa_vacinacao = ParamDetalheProgramaVacinacao::findOrFail(Crypt::decryptString($id));
        $detalhe_programa_vacinacao->delete();
        return redirect()->route('param.detalhe.programa.vacinacao.index');
    }

    protected function getParamViaAplicacao()
    {
        return ParamViaAplicacao::orderBy('descricao')->get();
    }
}
