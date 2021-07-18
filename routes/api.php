<?php

use App\Http\Controllers\UsuariosController;
use Illuminate\Http\Request;
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

Route::prefix('usuarios')->group(function() {
    Route::get('/', [ UsuariosController::class, 'buscar' ]);
    Route::post('/', [ UsuariosController::class, 'inserir' ]);
    Route::get('/{id:int}', [ UsuariosController::class, 'visualizar' ]);
    Route::put('/{id:int}', [ UsuariosController::class, 'editar' ]);
    Route::patch('/alterar-senha/{id:int}', [ UsuariosController::class, 'alterarSenha' ]);
    Route::delete('/{id:int}', [ UsuariosController::class, 'desativar' ]);
    Route::patch('/reativar/{id:int}', [ UsuariosController::class, 'reativar' ]);
});
