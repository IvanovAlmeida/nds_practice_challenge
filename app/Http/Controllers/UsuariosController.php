<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    public function buscar() : JsonResponse
    {
        $usuarios = Usuario::all();
        return response()->json($usuarios);
    }

    public function visualizar(int $id) : JsonResponse
    {
        $usuario = Usuario::find($id);

        if($usuario == null)
            return response()->json(["erro" => "not found"], 404);

        return response()->json($usuario);
    }

    public function inserir(Request $request) : JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'nome' => 'required|min:2|max:80|regex:/^[ ]*(.+[ ]+)+.+[ ]*$/i',
            'email' => 'required|unique:usuarios',
            'password' => 'required|min:6',
            'nascimento' => 'required|date'
        ]);

        if($validated->fails()) {
            return response()->json($validated->getMessageBag());
        }

        $dados = $request->all();
        $dados["password"] = Hash::make($dados["password"]);

        $usuario = Usuario::create($dados);

        return response()->json($usuario);
    }
}
