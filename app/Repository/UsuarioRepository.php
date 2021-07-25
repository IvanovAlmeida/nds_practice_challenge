<?php


namespace App\Repository;

use App\Models\Usuario;
use Illuminate\Support\Collection;
use App\Domain\Interfaces\Repository\IUsuarioRepository;

class UsuarioRepository implements IUsuarioRepository
{
    public function buscar(array $data): Collection
    {
        $query = Usuario::query();

        if(isset($data['nome']))
            $query->where('nome', 'like', '%' . $data['nome'] . '%');

        if(isset($data['email']))
            $query->where('email', 'like', '%' . $data['email'] . '%');

        return $query->withTrashed()->get();
    }

    public function buscarPorId($id): ?Usuario
    {
        return Usuario::withTrashed()->with('itens')->find($id);
    }

    public function inserir(array $dados): Usuario
    {
        return Usuario::create($dados);
    }

    public function atualizar(int $id, array $dados): bool
    {
        return Usuario::where('id', $id)
                        ->update($dados);
    }

    public function desativar(int $id): ?bool
    {
        $usuario = $this->buscarPorId($id);
        return $usuario?->trashed();
    }

    public function reativar(int $id): ?bool
    {
        $usuario = $this->buscarPorId($id);
        return $usuario?->restore();
    }
}
