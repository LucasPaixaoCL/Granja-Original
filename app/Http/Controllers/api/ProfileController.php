<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
class ProfileController extends Controller
{
    public function index(): View
    {
        return view('user.profile');
    }

    public function updateUserData(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id()
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return redirect()->back()->with('success_change_data', 'Usuário Atualizado!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|min:8|max:16',
            'new_password' => 'required|min:8|max:16|different:current_password',
            'new_password_confirmation' => 'required|same:new_password'
        ]);

        $user = auth()->user();

        // verifica se o password atual está correto para poder definir um novo password
        if (!password_verify($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'A Senha atual está incorreta!');
        }

        // atualizar a senha no banco de dados
        $user->password = bcrypt($request->new_password); // senha encriptada
        $user->save(); // grava na base de dados

        return redirect()->back()->with('success', 'Senha Atualizada!'); // redireciona
    }
}
