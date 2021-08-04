<?php

namespace App\Http\Controllers;

use App\Domain\Interfaces\INotificador;
use App\Domain\Notificacao\Notificacao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected INotificador $notificador;

    public function __construct(INotificador $notificador)
    {
        $this->notificador = $notificador;
    }

    protected function responseJson(?object $data, int $statusCode = 200): JsonResponse
    {
        if($this->notificador->temNotificacao()) {

            $erros = [];
            foreach ($this->notificador->obterNotificacoes() as $notificacao) {
                $erros[] = $notificacao->mensagem;
            }

            return response()->json([
                'status' => false,
                'erros' => $erros
            ], 400);
        }

        return response()->json([
            'status' => true,
            'data' => $data
        ], $statusCode);
    }
}
