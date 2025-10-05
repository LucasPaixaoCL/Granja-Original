<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfirmAccountController extends Controller
{
    public function confirmAccount($token)
    {
        $user = User::where('confirmation_token', $token)->first(); // valida o token

        if (!$user) { // caso não encontre nada, emite uma mensagem de que o token é inválido
            abort(403, 'O token é inválido!');
        }

        return view('auth.confirm-account', compact('user')); // apresenta a view de inclusão dos dados
    }

    public function confirmAccountSubmit(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:60', // tem que ter 60 caracteres
            'password' => 'required|confirmed|min:8|max:16|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
        ]);

        $user = User::where('confirmation_token', $request->token)->first(); // verifica se há algum token igual na base de dados
        $user->password = bcrypt($request->password); // encripta a senha
        $user->confirmation_token = null; // limpa o token para que o processo nao possa ser executado novamente
        $user->email_verified_at = now(); // atualiza o campo da data de verificacao
        $user->save(); // grava na base de dados

        return view('auth.welcome', compact('user'));
    }
}
