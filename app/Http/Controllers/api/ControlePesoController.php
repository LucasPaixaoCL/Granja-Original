<?php

namespace App\Http\Controllers\Api;

use App\Models\ControlePeso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ControlePesoController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $dados = [
            'pesos' => DB::table('controle_peso as cp')
                ->leftJoin('param_controle_peso as pcp', 'cp.param_controle_peso_id', '=', 'pcp.id')
                ->leftJoin('lotes as l', 'l.id', '=', 'cp.lote_id')
                ->where('l.id', 1)
                ->select([
                    'cp.id',
                    'l.num_lote',
                    'l.data_lote',
                    'cp.semana',
                    'cp.data_pesagem',
                    'cp.peso_real',
                    'pcp.peso_min',
                    'pcp.peso_max'
                ])
                ->orderBy('pcp.semana')
                ->get()
        ];
        return view('pesos.listar', compact('dados'));
    }

    public function create()
    {
        abort(403, 'Você não tem permissão para acessar esta página!');
    }

    public function store(Request $request)
    {
        abort(403, 'Você não tem permissão para acessar esta página!');
    }

    public function show($id)
    {
        abort(403, 'Você não tem permissão para acessar esta página!');
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $peso = ControlePeso::findOrFail(Crypt::decryptString($id));

        $peso_real = $peso->peso_real;
        $data_pesagem = $peso->data_pesagem;

        $dados = [
            'id' => $id,
            'peso_real' => $peso_real,
            'data_pesagem' => $data_pesagem
        ];

        return view('pesos.editar', compact('dados'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'peso_real' => 'required',
            'data_pesagem' => 'required'
        ]);

        $peso = ControlePeso::findOrFail(Crypt::decryptString($request->id));

        $peso->update([
            'peso_real' => $request->peso_real,
            'data_pesagem' => $request->data_pesagem,
            'updated_at' => now()
        ]);

        return redirect()->route('pesos.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        abort(403, 'Você não tem permissão para acessar esta página!');
    }

    public function destroy($id)
    {
        abort(403, 'Você não tem permissão para acessar esta página!');
    }
}
