<?php

namespace App\Domain\Notificacao;

use App\Domain\Interfaces\INotificador;
use Ramsey\Collection\Collection;

class Notificador implements INotificador
{
    private Collection $mensagens;

    public function __construct()
    {
        $this->mensagens = new Collection(Notificacao::class);
    }

    function notificar(Notificacao $mensagem): void
    {
        $this->mensagens->add($mensagem);
    }

    function obterNotificacoes(): array
    {
        return $this->mensagens->toArray();
    }

    function temNotificacao(): bool
    {
        return !$this->mensagens->isEmpty();
    }
}
