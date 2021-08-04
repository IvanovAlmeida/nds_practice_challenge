<?php

namespace App\Domain\Interfaces;

use App\Domain\Notificacao\Notificacao;

interface INotificador
{
    function notificar(Notificacao $mensagem): void;

    function obterNotificacoes(): array;

    function temNotificacao(): bool;
}
