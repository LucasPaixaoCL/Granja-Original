<?php

namespace App\Http\Controllers\Api;

use App\Models\Vacina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
class VacinasController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'vacinas' => DB::table('param_detalhes_programa_vacinacao as pdpv')
                ->join('param_programa_vacinacao as ppv', 'pdpv.param_programa_vacinacao_id', '=', 'ppv.id')
                ->leftJoin('vacinas as v', 'v.param_programa_vacinacao_id', '=', 'pdpv.id')
                ->leftJoin('lotes as l', function ($join) {
                    $join->on('l.id', '=', 'v.lote_id')
                        ->where('l.id', '=', 1);
                })
                ->join('param_via_aplicacao as pva', 'pva.id', '=', 'pdpv.param_via_aplicacao_id')
                ->select([
                    'v.id',
                    'pdpv.dia',
                    'pdpv.semana',
                    'pdpv.enfermidade',
                    'pdpv.param_via_aplicacao_id',
                    'pva.descricao',
                    'l.data_lote',
                    'v.data_prevista',
                    'v.data_realizada'
                ])
                ->limit(100)
                ->get()
        ];

        return view('vacinas.listar', compact('dados'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        // incluir o created_at

        return view('vacinas.adicionar');
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'nome' => 'required|min:3|max:30'
        ]);

        $vacina = new Vacina();
        $vacina->save();

        return redirect()->route('vacinas.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $vacina = Vacina::findOrFail(Crypt::decryptString($id));
        return view('vacinas.detalhes', compact('vacina'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $vacina = Vacina::findOrFail(Crypt::decryptString($id));

        $data_prevista = $vacina->data_prevista;
        $data_realizada = $vacina->data_realizada;

        $dados = [
            'id' => $id,
            'data_prevista' => $data_prevista,
            'data_realizada' => $data_realizada
        ];

        return view('vacinas.editar', compact('dados'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'data_realizada' => 'required'
        ]);

        $vacina = Vacina::findOrFail(Crypt::decryptString($request->id));

        $vacina->update([
            'data_realizada' => $request->data_realizada,
            'updated_at' => now()
        ]);

        return redirect()->route('vacinas.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $vacina = Vacina::findOrFail(Crypt::decryptString($id));
        return view('vacinas.confirm', compact('vacina'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $vacina = Vacina::findOrFail(Crypt::decryptString($id));
        $vacina->delete();
        return redirect()->route('vacinas.index');
    }
}
