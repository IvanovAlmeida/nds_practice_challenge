<?php


namespace App\Repository;


use App\Models\Item;
use Illuminate\Support\Collection;

class ItemRepository
{
    /**
     * @param array $dados
     * @return Collection
     */
    public function buscar(array $dados): Collection
    {
        $query = Item::query();

        if(isset($dados['incluirDesativados']))
            $query = Item::withTrashed()->query();

        if(isset($data['nome']))
            $query->where('nome', 'like', '%' . $data['nome'] . '%');

        if(isset($data['valor_min']))
            $query->where('valor', '>=', $data['valor']);

        if(isset($data['valor_max']))
            $query->where('valor', '<=', $data['valor']);

        if(isset($data['usuario_id']))
            $query->where('usuario_id', '=', $data['usuario_id']);

        return $query->get();
    }

    /**
     * @param $id
     * @return Item|null
     */
    public function buscarPorId($id): Item|null
    {
        return Item::withTrashed()->with('usuario')->find($id);
    }

    /**
     * @param array $dados
     * @return Item
     */
    public function inserir(array $dados): Item
    {
        return Item::create($dados);
    }

    /**
     * @param int $id
     * @param array $dados
     * @return bool
     */
    public function atualizar(int $id, array $dados): bool
    {
        return Item::where('id', $id)
                    ->update($dados);
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function desativar(int $id): bool|null
    {
        $item = $this->buscarPorId($id);
        return $item?->trashed();
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function reativar(int $id): bool|null
    {
        $item = $this->buscarPorId($id);
        return $item?->restore();
    }
}