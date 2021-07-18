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
            'valor' => 'required',
            'usuario_id' => 'required|exists:usuarios,id'
        ]);

        if($validated->fails()) {
            return response()->json($validated->getMessageBag(), 400);
        }

        $dados = $request->all(['nome', 'valor', 'usuario_id']);
        $item = $this->itemRepository->inserir($dados);

        return response()->json($item);
    }
}
