<?php

namespace App\Http\Controllers\Api;

use App\Models\Lote;
use App\Models\Nucleo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class NucleosController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'nucleos' => Nucleo::select('*')
                ->limit(10)
                ->get()
        ];

        return view('nucleos.listar', compact('dados'));
    }

    public function create()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'situacoes' => [
                ['id' => 1, 'descricao' => 'Ativo'],
                ['id' => 0, 'descricao' => 'Inativo'],
            ]
        ];

        return view('nucleos.adicionar', compact('dados'));
    }

    public function store(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30',
            'area_total' => 'required'
        ]);

        $nucleo = new Nucleo();
        $nucleo->user_id = 1;
        $nucleo->descricao = $request->descricao;
        $nucleo->area_total = $request->area_total;
        $nucleo->observacoes = $request->observacoes;
        $nucleo->situacao = $request->situacao;
        $nucleo->created_at = now();
        $nucleo->updated_at = now();
        $nucleo->save();

        return redirect()->route('nucleos.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function show($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $idDescriptografado = Crypt::decryptString($id);

        $dados = [
            'nucleo' => Nucleo::findOrFail($idDescriptografado),
            'total_lotes' => Lote::count()
        ];

        return view('nucleos.detalhes', compact('dados'));
    }

    public function edit($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $nucleo = Nucleo::findOrFail(Crypt::decryptString($id));

        $descricao = $nucleo->descricao;
        $area_total = $nucleo->area_total;
        $situacao = $nucleo->situacao;
        $observacoes = $nucleo->observacoes;

        $dados = [
            'id' => $id,
            'nucleo' => $nucleo,
            'situacoes' => [
                ['id' => 1, 'descricao' => 'Ativo'],
                ['id' => 0, 'descricao' => 'Inativo'],
            ]
        ];

        return view('nucleos.editar', compact('dados'));
    }

    public function update(Request $request)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $request->validate([
            'descricao' => 'required|min:3|max:30',
            'area_total' => 'required'
        ]);

        $nucleo = Nucleo::findOrFail(Crypt::decryptString($request->id));

        $nucleo->user_id = 1;
        $nucleo->descricao = $request->descricao;
        $nucleo->area_total = $request->area_total;
        $nucleo->observacoes = $request->observacoes;
        $nucleo->situacao = $request->situacao;
        $nucleo->updated_at = now();
        $nucleo->save();

        return redirect()->route('nucleos.index')->with('success', 'Gravado com sucesso!!!');
    }

    public function confirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        $dados = [
            'nucleo' => Nucleo::findOrFail(Crypt::decryptString($id))
        ];

        return view('nucleos.confirm', compact('dados'));
    }

    public function destroy($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        
        $nucleo = Nucleo::findOrFail(Crypt::decryptString($id));

        if ($nucleo->lotes()->exists()) {
            return back()->with('error', 'Não é possível excluir este núcleo pois há lotes associados a ele.');
        }

        $nucleo->delete();

        return redirect()->route('nucleos.index');
    }
}
