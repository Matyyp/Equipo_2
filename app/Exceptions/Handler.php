<?php

namespace App\Exceptions;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


public function render($request, Throwable $exception)
{
    if ($exception instanceof InvalidSignatureException) {
        // Si estás en un tenant
        if ($request->getHost() !== config('tenancy.central_domains')[0]) {
            return response()->view('tenant.errors.link_expired', [], 403);
        }

        // Si estás en el dominio central
        return response()->view('errors.link_expired', [], 403);
    }

    return parent::render($request, $exception);
}
}
