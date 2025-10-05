<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CsrfCookieController extends Controller
{
  /**
   * Gera/renova o token CSRF na sessão e retorna o cookie XSRF-TOKEN.
   * Rota: GET /api/csrf-cookie (com middleware 'web')
   */
  public function show(Request $request)
  {
    // Garante que a sessão tenha um token válido
    $request->session()->regenerateToken();

    $token = $request->session()->token();

    // Tempo do cookie em minutos (segue a sessão)
    $minutes = (int) config('session.lifetime', 120);

    // Monta o cookie XSRF-TOKEN (não HttpOnly para o axios ler e enviar no header X-XSRF-TOKEN)
    $cookie = cookie(
      name: 'XSRF-TOKEN',
      value: $token,
      minutes: $minutes,
      path: '/',
      domain: config('session.domain'),
      secure: $request->isSecure(),
      httpOnly: false,
      raw: false,
      sameSite: 'lax'
    );

    // 204 No Content com o cookie anexado
    return response()->noContent(204)->withCookie($cookie);
  }
}
