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

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

//Route::patch('/reativar/{id:int}', [ UsuariosController::class, 'reativar' ]);

Route::group(['middleware' => 'jwt.auth'], function() {
    Route::prefix('usuarios')->group(function() {
        Route::get('/', [ UsuariosController::class, 'buscar' ])->withoutMiddleware(['jwt.auth']);
        Route::get('/{id:int}', [ UsuariosController::class, 'visualizar' ])->withoutMiddleware(['jwt.auth']);

        Route::put('/', [ UsuariosController::class, 'editar' ]);
        Route::patch('/alterar-senha', [ UsuariosController::class, 'alterarSenha' ]);

        //Route::delete('/', [ UsuariosController::class, 'desativar' ]);
    });

    Route::prefix('itens')->group(function() {
        Route::get('/', [ ItensController::class, 'buscar' ])->withoutMiddleware(['jwt.auth']);
        Route::get('/{id:int}', [ ItensController::class, 'visualizar' ])->withoutMiddleware(['jwt.auth']);
        Route::post('/', [ ItensController::class, 'inserir' ]);
        Route::put('/{id:int}', [ ItensController::class, 'editar' ]);
        Route::delete('/{id:int}', [ ItensController::class, 'desativar' ]);
        Route::patch('/reativar/{id:int}', [ ItensController::class, 'reativar' ]);
    });


    Route::get('/auth/me', [AuthController::class, 'me']);
});
