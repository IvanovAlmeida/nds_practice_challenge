<?php

namespace App\Domain\Services;

use App\Domain\Exceptions\ValidationException;
use App\Domain\Interfaces\Repository\IUsuarioRepository;
use App\Domain\Interfaces\Services\IUsuarioService;
use App\Models\Usuario;

use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsuarioService implements IUsuarioService
{
    private IUsuarioRepository $usuarioRepository;

    public function __construct(IUsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * @inheritDoc
     * @throws ValidationException
     */
    function registrar(array $dados): ?Usuario
    {
        $validated = Validator::make($dados, [
            'nome' => 'required|min:2|max:80|regex:/^[ ]*(.+[ ]+)+.+[ ]*$/i',
            'email' => 'required|unique:usuarios',
            'password' => 'required|min:6',
            'nascimento' => 'required|date'
        ]);

        if($validated->fails()) {
            throw new ValidationException($validated->getMessageBag()->getMessages());
        }

        $dados["password"] = Hash::make($dados["password"]);
        return $this->usuarioRepository->inserir($dados);
    }

    /**
     * @inheritDoc
     */
    function editar(int $id, array $dados): void
    {
        $validated = Validator::make($dados, [
            'nome' => 'required|min:2|max:80|regex:/^[ ]*(.+[ ]+)+.+[ ]*$/i',
            'email' => [
                'required',
                Rule::unique('usuarios')->ignore($id)
            ],
            'nascimento' => 'required|date'
        ]);

        if($validated->fails()) {
            throw new ValidationException($validated->getMessageBag()->getMessages());
        }

        if(!$this->usuarioRepository->atualizar($id, $dados)) {
            throw new Exception("Não foi possível atualizar!");
        }
    }

    /**
     * @inheritDoc
     */
    function alterarSenha(int $id, array $dados): void
    {
        $validated = Validator::make($dados, [
            'password' => 'required',
            'newPassword' => 'required|min:6'
        ]);

        if($validated->fails()) {
            throw new ValidationException($validated->getMessageBag()->getMessages());
        }

        $usuario = $this->usuarioRepository->buscarPorId($id);
        if(!Hash::check($dados['password'], $usuario->password)) {
            throw new ValidationException(["Senha inválida!"]);
        }

        $password = Hash::make($dados['newPassword']);
        if(!$this->usuarioRepository->atualizar($usuario->id, ['password' => $password])) {
            throw new Exception("Não foi possível alterar senha!");
        }
    }
}
