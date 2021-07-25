<?php


namespace App\Domain\Interfaces\Repository;

use App\Models\Item;
use Illuminate\Support\Collection;

/**
 * Interface IItemRepository
 * @package App\Domain\Interfaces\Repository
 */
interface IItemRepository
{
    /**
     * @param array $dados
     * @return Collection
     */
    function buscar(array $dados): Collection;

    /**
     * @param int $id
     * @return Item|null
     */
    function buscarPorId(int $id): ?Item;

    /**
     * @param int $id
     * @return Item|null
     */
    function buscarPorIdComUsuario(int $id): ?Item;

    /**
     * @param int $id
     * @param int $usuario_id
     * @return Item|null
     */
    function buscarPorIdEUsuario(int $id, int $usuario_id): ?Item;

    /**
     * @param array $dados
     * @return Item
     */
    function inserir(array $dados): Item;

    /**
     * @param int $id
     * @param array $dados
     * @return bool
     */
    function atualizar(int $id, array $dados): bool;

    /**
     * @param int $id
     * @return bool|null
     */
    function desativar(int $id): ?bool;

    /**
     * @param int $id
     * @return bool|null
     */
    function reativar(int $id): ?bool;
}
