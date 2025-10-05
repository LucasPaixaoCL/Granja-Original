<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
  /**
   * POST /api/auth/login
   * Requer que o front chame /sanctum/csrf-cookie antes (para setar XSRF-TOKEN).
   * Usa sessão + cookie (Sanctum SPA), NÃO tokens pessoais.
   */
  public function login(Request $request)
  {
    $data = $request->validate([
      'email'    => ['required', 'email'],
      'password' => ['required', 'string'],
      'remember' => ['sometimes', 'boolean'],
    ]);

    if (!Auth::attempt(
      ['email' => $data['email'], 'password' => $data['password']],
      $data['remember'] ?? false
    )) {
      throw ValidationException::withMessages([
        'email' => ['Credenciais inválidas.'],
      ]);
    }

    // Protege contra session fixation
    $request->session()->regenerate();

    return response()->json([
      'message' => 'Autenticado',
      'user'    => $request->user(),
    ], 200);
  }

  /**
   * GET /api/auth/me
   * Retorna o usuário autenticado (via cookie Sanctum).
   */
  public function me(Request $request)
  {
    return response()->json($request->user(), 200);
  }

  /**
   * POST /api/auth/logout
   * Encerra a sessão (cookie).
   */
  public function logout(Request $request)
  {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['message' => 'Logout efetuado'], 200);
  }

  /**
   * GET /api/user/profile
   * Perfil do usuário logado (equivalente ao seu ProfileController@index, porém JSON).
   */
  public function profile(Request $request)
  {
    return response()->json([
      'user' => $request->user(),
    ], 200);
  }

  /**
   * PUT /api/user/profile
   * Atualiza nome/email do usuário logado.
   */
  public function updateUserData(Request $request)
  {
    $user = $request->user();

    $validated = $request->validate([
      'name'  => ['required', 'string', 'min:3', 'max:255'],
      'email' => [
        'required',
        'email',
        'max:255',
        Rule::unique('users', 'email')->ignore($user->id),
      ],
    ]);

    $user->fill($validated)->save();

    return response()->json([
      'message' => 'Usuário atualizado',
      'user'    => $user->fresh(),
    ], 200);
  }

  /**
   * PUT /api/user/password
   * Atualiza senha do usuário logado, validando a senha atual.
   */
  public function updatePassword(Request $request)
  {
    $user = $request->user();

    $validated = $request->validate([
      'current_password'      => ['required', 'string', 'min:8', 'max:64'],
      'new_password'          => ['required', 'string', 'min:8', 'max:64', 'different:current_password'],
      'new_password_confirmation' => ['required', 'same:new_password'],
    ]);

    if (!Hash::check($validated['current_password'], $user->password)) {
      return response()->json([
        'message' => 'A senha atual está incorreta.',
      ], 422);
    }

    $user->password = Hash::make($validated['new_password']);
    $user->save();

    return response()->json([
      'message' => 'Senha atualizada',
    ], 200);
  }
}
