<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class AuthApiController extends Controller
{
  /**
   * POST /api/auth/login
   * Requer /sanctum/csrf-cookie antes (front deve enviar X-XSRF-TOKEN).
   * Usa sessão + cookies (Sanctum SPA).
   */
  public function login(Request $request)
  {
    $data = $request->validate([
      'email'    => ['required', 'email'],
      'password' => ['required', 'string'],
      'remember' => ['sometimes', 'boolean'],
    ]);

    $ok = Auth::guard('web')->attempt(
      ['email' => $data['email'], 'password' => $data['password']],
      (bool) ($data['remember'] ?? false) // <- “Lembrar-me”
    );

    if (!$ok) {
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
    $user = $request->user();
    if (!$user) {
      return response()->json(['message' => 'Unauthenticated.'], 401);
    }
    return response()->json($user, 200);
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
   */
  public function profile(Request $request)
  {
    return response()->json([
      'user' => $request->user(),
    ], 200);
  }

  /**
   * PUT /api/user/profile
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
   */
  public function updatePassword(Request $request)
  {
    $user = $request->user();

    $validated = $request->validate([
      // valida a senha atual no guard web
      'current_password'          => ['required', 'current_password:web'],
      'new_password'              => ['required', 'string', 'min:8', 'max:64', 'different:current_password'],
      'new_password_confirmation' => ['required', 'same:new_password'],
    ]);

    $user->password = Hash::make($validated['new_password']);
    $user->save();

    return response()->json(['message' => 'Senha atualizada'], 200);
  }

  /**
   * POST /api/auth/forgot-password
   * Envia e-mail com link de redefinição (token).
   */
  public function forgotPassword(Request $request)
  {
    $validated = $request->validate([
      'email' => ['required', 'email'],
    ]);

    $status = Password::sendResetLink(['email' => $validated['email']]);

    if ($status === Password::RESET_LINK_SENT) {
      return response()->json(['message' => __($status)], 200);
    }

    return response()->json(['message' => __($status)], 422);
  }

  /**
   * POST /api/auth/reset-password
   * Reseta a senha usando token enviado por e-mail.
   */
  public function resetPassword(Request $request)
  {
    $validated = $request->validate([
      'token'                 => ['required', 'string'],
      'email'                 => ['required', 'email'],
      'password'              => ['required', 'confirmed', 'min:8', 'max:64'],
      // password_confirmation vem do 'confirmed'
    ]);

    $status = Password::reset(
      [
        'email'                 => $validated['email'],
        'password'              => $validated['password'],
        'password_confirmation' => $request->password_confirmation,
        'token'                 => $validated['token'],
      ],
      function ($user) use ($validated) {
        $user->forceFill([
          'password'       => Hash::make($validated['password']),
          'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));
      }
    );

    if ($status === Password::PASSWORD_RESET) {
      return response()->json(['message' => __($status)], 200);
    }

    return response()->json(['message' => __($status)], 422);
  }
}
