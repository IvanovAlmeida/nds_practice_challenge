<?php

namespace App\Http\Controllers;

use App\Repository\UsuarioRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private UsuarioRepository $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
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

    public function register(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'nome' => 'required|min:2|max:80|regex:/^[ ]*(.+[ ]+)+.+[ ]*$/i',
            'email' => 'required|unique:usuarios',
            'password' => 'required|min:6',
            'nascimento' => 'required|date'
        ]);

        if($validated->fails()) {
            return response()->json($validated->getMessageBag(), 400);
        }

        $dados = $request->all();
        $dados["password"] = Hash::make($dados["password"]);

        $usuario = $this->usuarioRepository->inserir($dados);

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
