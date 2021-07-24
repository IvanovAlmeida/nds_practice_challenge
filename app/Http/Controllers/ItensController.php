<?php

namespace App\Http\Controllers;

use App\Repository\ItemRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItensController extends Controller
{
    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function buscar(Request $request): JsonResponse
    {

        $itens = $this->itemRepository->buscar($request->query->all());
        return response()->json($itens);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function visualizar(int $id): JsonResponse
    {
        $item = $this->itemRepository->buscarPorId($id);
        if($item == null)
            return response()->json(["erro" => "not found"], 404);

        return response()->json($item);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function inserir(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'nome' => 'required|min:2|max:25',
            'valor' => 'required'
        ]);

        if($validated->fails()) {
            return response()->json($validated->getMessageBag(), 400);
        }

        $dados = $request->all(['nome', 'valor']);
        $dados['usuario_id'] = auth()->user()->id;
        $item = $this->itemRepository->inserir($dados);

        return response()->json($item);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function editar(Request $request, int $id): JsonResponse
    {
        $item = $this->itemRepository->buscarPorIdEUsuario($id, auth()->user()->id);
        if($item == null)
            return response()->json(["erro" => "not found"], 404);

        $validated = Validator::make($request->all(), [
            'nome' => 'required|min:2|max:25',
            'valor' => 'required'
        ]);

        if($validated->fails()) {
            return response()->json($validated->getMessageBag(), 400);
        }

        $dados = $request->all(['nome', 'valor']);
        if(!$this->itemRepository->atualizar($id, $dados)) {
            return response()->json(['Não foi possível alterar o item!'], 500);
        }

        $item->fill($dados);
        return response()->json($item);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function desativar(int $id): JsonResponse
    {
        $item = $this->itemRepository->buscarPorIdEUsuario($id, auth()->user()->id);
        if($item == null)
            return response()->json(["erro" => "not found"], 404);

        $this->itemRepository->desativar($id);
        return response()->json(["msg" => "Item desativado com sucesso!"]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function reativar(int $id): JsonResponse
    {
        $item = $this->itemRepository->buscarPorIdEUsuario($id, auth()->user()->id);
        if($item == null)
            return response()->json(["erro" => "not found"], 404);

        $this->itemRepository->reativar($id);
        return response()->json(["msg" => "Item reativado com sucesso!"]);
    }
}
