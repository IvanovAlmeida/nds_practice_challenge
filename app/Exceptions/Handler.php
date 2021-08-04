<?php

namespace App\Exceptions;

use App\Domain\Exceptions\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof UnauthorizedHttpException) {
            $preException = $e->getPrevious();
            if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['error' => 'TOKEN_EXPIRED'], 401);
            } else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['error' => 'TOKEN_INVALID'], 401);
            } else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return response()->json(['error' => 'TOKEN_BLACKLISTED'], 401);
            }

            if ($e->getMessage() === 'Token not provided') {
                return response()->json(['error' => 'Token not provided'], 401);
            }
        }

        if($e->getPrevious() instanceof NotFoundHttpException) {
            return response()->json([
                'status' => false,
                'erros' => $e->getErros()
            ], $e->getStatusCode());
        }

        if ($e instanceof ValidationException) {
            return response()->json([
                'status' => false,
                'erros' => $e->getErros()
            ], 501);
        }


        return response()->json([
            'status' => false,
            'erros' => "Ops, ocorreu um erro no servidor"
        ], 500);

        //return parent::render($request, $e); // TODO: Change the autogenerated stub
    }
}
