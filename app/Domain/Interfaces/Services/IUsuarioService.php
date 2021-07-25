<?php


namespace App\Domain\Interfaces\Services;

use App\Domain\Exceptions\ValidationException;
use Exception;
use App\Models\Usuario;

/**
 * Interface IUsuarioService
 * @package App\Domain\Interfaces\Services
 */
interface IUsuarioService
{
    /**
     * @param array $dados
     * @throws ValidationException
     * @throws Exception
     * @return array|null
     */
    function registrar(array $dados): ?Usuario;

    /**
     * @param int $id
     * @param array $dados
     * @throws ValidationException
     * @throws Exception
     * @return void
     */
    function editar(int $id, array $dados): void;

    /**
     * @param int $id
     * @param array $dados
     * @throws ValidationException
     * @throws Exception
     * @return void
     */
    function alterarSenha(int $id, array $dados): void;
}
