<?php

namespace App\Http\Controllers;

use App\Domain\Exceptions\ValidationException;
use App\Domain\Interfaces\Services\IItemService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Domain\Interfaces\Repository\IItemRepository;

class ItensController extends Controller
{
    private IItemService $itemService;
    private IItemRepository $itemRepository;

    public function __construct(IItemService $itemService, IItemRepository $itemRepository)
    {
        $this->itemService = $itemService;
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
     * @throws Exception
     */
    public function inserir(Request $request): JsonResponse
    {
        try {
            $dados = $request->all(['nome', 'valor']);
            $dados['usuario_id'] = auth()->user()->id;
            $item = $this->itemService->inserir($dados);
        } catch ( ValidationException $e ) {
            return response()->json([
                'status' => false,
                'erros' => $e->getErros()
            ], 400);
        }

        return response()->json($item);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function editar(Request $request, int $id): JsonResponse
    {
        try {
            $dados = $request->only(['nome', 'valor']);
            $this->itemService->atualizar($id, auth()->user()->id, $dados);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'erros' => $e->getErros()
            ], 400);
        }

        $dados['id'] = $id;
        return response()->json([
            'status' => true,
            'data' => $dados
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function desativar(int $id): JsonResponse
    {
        $this->itemService->desativar($id, auth()->user()->id);
        return response()->json(["msg" => "Item desativado com sucesso!"]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function reativar(int $id): JsonResponse
    {
        $this->itemService->reativar($id, auth()->user()->id);
        return response()->json(["msg" => "Item reativado com sucesso!"]);
    }
}
