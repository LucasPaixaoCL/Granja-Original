<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class ColaboratorsController extends Controller
{
    public function index()
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');
        $colaborators = User::with('detail', 'department')->where('role', '<>', 'admin')->get();
        return view('colaborators.admin-all-colaborators', compact('colaborators'));
    }

    public function details($id)
    {
        Auth::user()->can('admin', 'rh') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        // verificar se o id é o mesmo do usuário // nao pode mostrar os seus próprios detalhes
        if (Auth::user()->id === $id) {
            return redirect()->route('home');
        }

        $colaborator = User::with('detail', 'department')->where('id', $id)->first();

        return view('colaborators.show-details', compact('colaborator'));
    }

    public function delete($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        if (Auth::user()->id === $id) {
            return redirect()->route('home');
        }

        $colaborator = User::findOrFail(Crypt::decryptString($id));

        return view('colaborators.confirm_delete', compact('colaborator')); // view de confirmação
    }

    public function deleteConfirm($id)
    {
        Auth::user()->can('admin') ?: abort(403, 'Você não tem permissão para acessar esta página!');

        if (Auth::user()->id === $id) {
            return redirect()->route('home');
        }

        $colaborator = User::findOrFail(Crypt::decryptString($id));

        $colaborator->delete();

        return redirect()->route('colaborators.all');
    }
}
