<?php

namespace App\Http\Controllers\Api;

use App\Models\ParamFaseAve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class ParamFaseAveController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'fases' => ParamFaseAve::orderBy('semana_inicial', 'asc')->get()
        ];

        return view('parametros.fases_ave.listar', compact('dados'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        return view('parametros.fases_ave.adicionar');
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30',
            'semana_inicial' => 'required',
            'semana_final' => 'required'
        ]);

        $param = new ParamFaseAve();
        $param->descricao = $request->descricao;
        $param->semana_inicial = $request->semana_inicial;
        $param->semana_final = $request->semana_final;
        $param->save();

        return redirect()->route('param.fases.ave.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados  = [
            'fase_ave' => ParamFaseAve::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.fases_ave.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'fase_ave' => ParamFaseAve::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.fases_ave.editar', compact('dados'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30',
            'semana_inicial' => 'required',
            'semana_final' => 'required'
        ]);

        $fase_ave = ParamFaseAve::findOrFail($request->id);

        $fase_ave->update([
            'descricao' => $request->descricao,
            'semana_inicial' => $request->semana_inicial,
            'semana_final' => $request->semana_final
        ]);

        return redirect()->route('param.fases.ave.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'fase_ave' => ParamFaseAve::findOrFail(Crypt::decryptString($id))
        ];

        return view('parametros.fases_ave.confirmar', compact('dados'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $fase = ParamFaseAve::findOrFail(Crypt::decryptString($id));
        $fase->delete();
        return redirect()->route('param.fases.ave.index');
    }
}
