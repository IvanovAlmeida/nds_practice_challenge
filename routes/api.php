<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItensController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::group([ 'middleware' => 'jwt.auth'], function() {
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UsuariosController::class, 'buscar']);
        Route::post('/', [UsuariosController::class, 'inserir']);
        Route::get('/{id:int}', [UsuariosController::class, 'visualizar']);
        Route::put('/', [UsuariosController::class, 'editar']);
        Route::patch('/alterar-senha', [UsuariosController::class, 'alterarSenha']);
        //Route::delete('/{id:int}', [UsuariosController::class, 'desativar']);
        //Route::patch('/reativar/{id:int}', [UsuariosController::class, 'reativar']);
    });

    Route::prefix('itens')->group(function () {
        Route::get('/', [ItensController::class, 'buscar']);
        Route::get('/{id:int}', [ItensController::class, 'visualizar']);
        Route::post('/', [ItensController::class, 'inserir']);
        Route::put('/{id:int}', [ItensController::class, 'editar']);
        Route::delete('/{id:int}', [ItensController::class, 'desativar']);
        Route::patch('/reativar/{id:int}', [ItensController::class, 'reativar']);
    });

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});
