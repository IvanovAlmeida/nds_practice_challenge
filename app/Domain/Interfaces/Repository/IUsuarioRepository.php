<?php

namespace App\Domain\Interfaces\Repository;;

use App\Models\Usuario;
use Illuminate\Support\Collection;

/**
 * Interface IUsuarioRepository
 * @package App\Domain\Interfaces\Repository
 */
interface IUsuarioRepository
{
    /**
     * @param array $data
     * @return Collection
     */
    function buscar(array $data): Collection;

    /**
     * @param $id
     * @return Usuario|null
     */
    function buscarPorId($id): ?Usuario;

    /**
     * @param array $dados
     * @return Usuario
     */
    function inserir(array $dados): Usuario;

    /**
     * @param int $id
     * @param array $dados
     * @return bool
     */
    function atualizar(int $id, array $dados): bool;
}
