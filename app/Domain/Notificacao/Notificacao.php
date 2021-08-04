<?php

namespace App\Domain\Notificacao;

class Notificacao
{
    public string $mensagem;

    public function __construct(string $mensagem)
    {
        $this->mensagem = $mensagem;
    }
}
