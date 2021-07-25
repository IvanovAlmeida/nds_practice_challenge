<?php

namespace App\Http\Controllers;

use App\Domain\Exceptions\ValidationException;
use App\Domain\Interfaces\Repository\IUsuarioRepository;
use App\Domain\Interfaces\Services\IUsuarioService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    private IUsuarioService $usuarioService;
    private IUsuarioRepository $usuarioRepository;

    public function __construct(IUsuarioService $usuarioService, IUsuarioRepository $usuarioRepository)
    {
        $this->usuarioService = $usuarioService;
        $this->usuarioRepository = $usuarioRepository;
    }

    public function buscar(Request $request): JsonResponse
    {
        $usuarios = $this->usuarioRepository->buscar($request->query->all());
        return response()->json($usuarios);
    }

    public function visualizar(int $id): JsonResponse
    {
        $usuario = $this->usuarioRepository->buscarPorId($id);
        if($usuario == null)
            return response()->json(["erro" => "not found"], 404);

        return response()->json($usuario);
    }

    /**
     * @throws Exception
     */
    public function editar(Request $request): JsonResponse
    {
        $dados = $request->only(['nome', 'email', 'nascimento']);

        try {
            $this->usuarioService->editar(auth()->user()->id, $dados);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'erros' => $e->getErros()
            ], 400);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => auth()->user()->id,
                ...$dados
            ]
        ]);
    }

    /**
     * @throws Exception
     */
    public function alterarSenha(Request $request): JsonResponse
    {
        try {
            $dados = $request->only(['password', 'newPassword']);
            $this->usuarioService->alterarSenha(auth()->user()->id, $dados);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'erros' => $e->getErros()
            ], 400);
        }

        return response()->json(["msg" => "Senha alterada com sucesso!"]);
    }
}
