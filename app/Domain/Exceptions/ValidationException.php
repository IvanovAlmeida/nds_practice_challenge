<?php


namespace App\Domain\Exceptions;


use Throwable;

class ValidationException extends \Exception
{
    private array $erros;

    public function __construct(array $erros, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->erros = $erros;

        parent::__construct($message, $code, $previous);
    }

    public function getErros(): array
    {
        return $this->erros;
    }
}
