<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Repository\UsuarioRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\Rule;

class UsuariosController extends Controller
{
    private UsuarioRepository $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepository)
    {
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



    public function editar(Request $request): JsonResponse
    {
        $usuario = $this->usuarioRepository->buscarPorId(auth()->user()->id);
        if($usuario == null)
            return response()->json(["erro" => "not found"], 404);

        $validated = Validator::make($request->all(), [
            'nome' => 'required|min:2|max:80|regex:/^[ ]*(.+[ ]+)+.+[ ]*$/i',
            'email' => [
                'required',
                Rule::unique('usuarios')->ignore($usuario->id)
            ],
            'nascimento' => 'required|date'
        ]);

        if($validated->fails()) {
            return response()->json($validated->getMessageBag(), 400);
        }

        $dados = $request->only(['nome', 'email', 'nascimento']);
        if(!$this->usuarioRepository->atualizar($usuario->id, $dados)) {
            return response()->json(["erro" => "Não foi possível alterar usuário!"], 500);
        }

        $usuario->fill($dados);
        return response()->json($usuario);
    }

    public function alterarSenha(Request $request): JsonResponse
    {
        $usuario = $this->usuarioRepository->buscarPorId(auth()->user()->id);
        if($usuario == null)
            return response()->json(["erro" => "not found"], 404);

        $validated = Validator::make($request->all(), [
            'password' => 'required',
            'newPassword' => 'required|min:6'
        ]);

        if($validated->fails()) {
            return response()->json($validated->getMessageBag(), 400);
        }

        if(!Hash::check($request->password, $usuario->password)) {
            return response()->json(["erro" => "Senha inválida!"], 401);
        }

        $password = Hash::make($request->newPassword);
        if(!$this->usuarioRepository->atualizar($usuario->id, ['password' => $password])) {
            return response()->json(["erro" => "Não foi possível alterar senha do usuário!"], 500);
        }

        return response()->json(["msg" => "Senha alterada com sucesso!"]);
    }

    public function desativar(int $id): JsonResponse
    {
        $usuario = $this->usuarioRepository->buscarPorId($id);
        if($usuario == null)
            return response()->json(["erro" => "not found"], 404);

        $this->usuarioRepository->desativar($id);
        return response()->json(["msg" => "Usuário desativado com sucesso!"]);
    }

    public function reativar(int $id): JsonResponse
    {
        $usuario = $this->usuarioRepository->buscarPorId($id);
        if($usuario == null)
            return response()->json(["erro" => "not found"], 404);

        $this->usuarioRepository->reativar($id);
        return response()->json(["msg" => "Usuário reativado com sucesso!"]);
    }
}
