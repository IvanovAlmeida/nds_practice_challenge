<?php


namespace App\Domain\Interfaces\Services;


use App\Domain\Exceptions\ValidationException;
use App\Models\Item;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface IItemService
 * @package App\Domain\Interfaces\Services
 */
interface IItemService
{
    /**
     * @param array $dados
     * @throws ValidationException
     * @throws Exception
     * @return Item
     */
    function inserir(array $dados): Item;

    /**
     * @param int $id
     * @param int $usuario_id
     * @param array $dados
     * @throws ValidationException
     * @throws ModelNotFoundException
     * @throws Exception
     * @return void
     */
    function atualizar(int $id, int $usuario_id, array $dados): void;

    /**
     * @param int $id
     * @param int $usuario_id
     * @throws ModelNotFoundException
     * @return bool
     */
    function desativar(int $id, int $usuario_id): bool;

    /**
     * @param int $id
     * @param int $usuario_id
     * @throws ModelNotFoundException
     * @return bool
     */
    function reativar(int $id, int $usuario_id): bool;
}
