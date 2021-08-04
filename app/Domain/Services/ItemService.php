<?php


namespace App\Domain\Services;


use App\Domain\Exceptions\ValidationException;
use App\Domain\Interfaces\INotificador;
use App\Domain\Interfaces\Repository\IItemRepository;
use App\Domain\Interfaces\Services\IItemService;
use App\Domain\Notificacao\Notificacao;
use App\Models\Item;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class ItemService implements IItemService
{
    private IItemRepository $itemRepository;
    private INotificador $notificador;

    public function __construct(IItemRepository $itemRepository, INotificador $notificador)
    {
        $this->itemRepository = $itemRepository;
        $this->notificador = $notificador;
    }

    /**
     * @inheritDoc
     */
    function inserir(array $dados): ?Item
    {
        $validated = Validator::make($dados, [
            'nome' => 'required|min:2|max:25',
            'valor' => 'required',
            'usuario_id' => 'required'
        ]);

        if($validated->fails()) {
            foreach ($validated->getMessageBag()->getMessages() as $messages) {
                foreach ($messages as $item) {
                    $this->notificador->notificar(new Notificacao($item));
                }
            }

            return null;
        }

        return $this->itemRepository->inserir($dados);
    }

    /**
     * @inheritDoc
     */
    function atualizar(int $id, int $usuario_id, array $dados): void
    {
        $item = $this->itemRepository->buscarPorIdEUsuario($id, $usuario_id);
        if($item == null)
            throw new ModelNotFoundException();

        $validated = Validator::make($dados, [
            'nome' => 'required|min:2|max:25',
            'valor' => 'required'
        ]);

        if($validated->fails()) {
            throw new ValidationException($validated->getMessageBag()->getMessages());
        }

        if(!$this->itemRepository->atualizar($id, $dados)) {
            throw new Exception("Não foi possível atualizar!");
        }
    }

    /**
     * @param int $id
     * @param int $usuario_id
     * @return bool
     */
    function desativar(int $id, int $usuario_id): bool
    {
        $item = $this->itemRepository->buscarPorIdEUsuario($id, $usuario_id);

        if($item == null)
            throw new ModelNotFoundException();

        return $this->itemRepository->desativar($id);
    }

    /**
     * @param int $id
     * @param int $usuario_id
     * @return bool
     */
    function reativar(int $id, int $usuario_id): bool
    {
        $item = $this->itemRepository->buscarPorIdEUsuario($id, $usuario_id);

        if($item == null)
            throw new ModelNotFoundException();

        return  $this->itemRepository->reativar($id);
    }
}
