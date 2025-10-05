<?php

use Illuminate\Support\Facades\Route;

// Controllers em App\Http\Controllers\Api\...
use App\Http\Controllers\Api\AuthApiController;


use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\RhUserController;
use App\Http\Controllers\Api\NucleosController;
use App\Http\Controllers\Api\GalpoesController;
use App\Http\Controllers\Api\LotesController;
use App\Http\Controllers\Api\ColetasController;
use App\Http\Controllers\Api\DescartesController;
use App\Http\Controllers\Api\MortesController;
use App\Http\Controllers\Api\VacinasController;
use App\Http\Controllers\Api\ControlePesoController;
use App\Http\Controllers\Api\ClientesController;
use App\Http\Controllers\Api\FuncionariosController;
use App\Http\Controllers\Api\ForncedoresController;
use App\Http\Controllers\Api\FormasPgtoController;
use App\Http\Controllers\Api\FormatosController;
use App\Http\Controllers\Api\VendasController;
use App\Http\Controllers\Api\DespesasController;
use App\Http\Controllers\Api\CsrfCookieController;

use App\Http\Controllers\Api\ParamProgramaLuzController;
use App\Http\Controllers\Api\ParamProgramaVacinacaoController;
use App\Http\Controllers\Api\ParamDetalheProgramaVacinacaoController;
use App\Http\Controllers\Api\ParamLinhagensController;
use App\Http\Controllers\Api\ParamControlePesoController;
use App\Http\Controllers\Api\ParamConsumoAguaController;
use App\Http\Controllers\Api\ParamConsumoRacaoController;
use App\Http\Controllers\Api\ParamMortalidadeController;
use App\Http\Controllers\Api\ParamFaseAveController;
use App\Http\Controllers\Api\ParamTipoDespesaController;
use App\Http\Controllers\Api\ParamNaturezaDespesaController;
use App\Http\Controllers\Api\ParamCategoriaDespesaController;
// routes/api.php
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Guest (sem sessão) — confirmação e login
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Confirmação de conta (fluxo por API)
    // Auth
    Route::post('/auth/login',             [AuthApiController::class, 'login']);
    Route::post('/auth/forgot-password',   [AuthApiController::class, 'forgotPassword']); // opcional
    Route::post('/auth/reset-password',    [AuthApiController::class, 'resetPassword']);  // opcional
});
// Endpoint para obter o cookie de CSRF (Sanctum) via /api/csrf-cookie
Route::get('/csrf-cookie', [CsrfCookieController::class, 'show'])
    ->name('api.csrf-cookie')
    ->middleware('web'); // precisa do middleware 'web' para setar o cookie
// Sem sessão
Route::post('/auth/login', [AuthApiController::class, 'login'])->middleware('guest');


Route::get('/debug/csrf', function (Request $r) {
    return response()->json([
        'session_token' => $r->session()->token(),
        'header_token'  => $r->header('X-XSRF-TOKEN'),
        'cookies'       => array_keys($_COOKIE),
        'origin'        => $r->headers->get('origin'),
        'referer'       => $r->headers->get('referer'),
    ]);
})->middleware('web');

// Com sessão (cookie Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me',          [AuthApiController::class, 'me']);
    Route::post('/auth/logout',     [AuthApiController::class, 'logout']);

    Route::get('/user/profile',     [AuthApiController::class, 'profile']);
    Route::put('/user/profile',     [AuthApiController::class, 'updateUserData']);
    Route::put('/user/password',    [AuthApiController::class, 'updatePassword']);

    /*
|--------------------------------------------------------------------------
| Autenticados por cookie (Sanctum SPA)
|--------------------------------------------------------------------------
*/
    Route::middleware('auth:sanctum')->group(function () {
        // Sessão
        Route::get('/auth/me',     [AuthApiController::class, 'me']);
        Route::post('/auth/logout', [AuthApiController::class, 'logout']);

        // Recursos principais — apenas endpoints JSON (index, store, show, update, destroy)
        Route::apiResource('departments',      DepartmentController::class);
        Route::apiResource('rh-users',         RhUserController::class);
        Route::apiResource('nucleos',          NucleosController::class);
        Route::apiResource('galpoes',          GalpoesController::class);
        Route::apiResource('lotes',            LotesController::class);
        Route::apiResource('coletas',          ColetasController::class);
        Route::apiResource('descartes',        DescartesController::class);
        Route::apiResource('mortes',           MortesController::class);
        Route::apiResource('vacinas',          VacinasController::class);
        Route::apiResource('pesos',            ControlePesoController::class);

        Route::apiResource('clientes',         ClientesController::class);
        Route::apiResource('funcionarios',     FuncionariosController::class);
        Route::apiResource('fornecedores',     ForncedoresController::class);
        Route::apiResource('formas-pgto',      FormasPgtoController::class);
        Route::apiResource('formatos',         FormatosController::class);
        Route::apiResource('vendas',           VendasController::class);
        Route::apiResource('despesas',         DespesasController::class);

        // Parâmetros (namespaces agrupados por prefixo 'param/')
        Route::prefix('param')->group(function () {
            Route::apiResource('programa-luz',               ParamProgramaLuzController::class);
            Route::apiResource('programa-vacinacao',         ParamProgramaVacinacaoController::class);
            Route::apiResource('detalhe-programa-vacinacao', ParamDetalheProgramaVacinacaoController::class);
            Route::apiResource('linhagens',                  ParamLinhagensController::class);
            Route::apiResource('controle-peso',              ParamControlePesoController::class);
            Route::apiResource('consumo-agua',               ParamConsumoAguaController::class);
            Route::apiResource('consumo-racao',              ParamConsumoRacaoController::class);
            Route::apiResource('mortalidade',                ParamMortalidadeController::class);
            Route::apiResource('fases-ave',                  ParamFaseAveController::class);
            Route::apiResource('tipo-despesa',               ParamTipoDespesaController::class);
            Route::apiResource('natureza-despesa',           ParamNaturezaDespesaController::class);
            Route::apiResource('categoria-despesa',          ParamCategoriaDespesaController::class);
        });

        // Rotas calculadas/relatórios
        Route::get('/grafico-mortes', [MortesController::class, 'graficoMortes']);
    });
});
