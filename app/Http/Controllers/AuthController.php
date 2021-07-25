<?php

namespace App\Http\Controllers;

use App\Domain\Exceptions\ValidationException;
use App\Domain\Interfaces\Services\IUsuarioService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private IUsuarioService $usuarioService;

    public function __construct(IUsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }

    public function login()
    {
        $credentials = request()->only(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json([
            'token' => $token,
            'expires' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    /**
     * @throws Exception
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $usuario = $this->usuarioService->registrar($request->only([
                'nome', 'email', 'password', 'nascimento'
            ]));
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'erros' => $e->getErros()
            ], 400);
        }

        $token = JWTAuth::fromUser($usuario);
        return response()->json([
            'token' => $token,
            'usuario' => $usuario,
            'expires' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
