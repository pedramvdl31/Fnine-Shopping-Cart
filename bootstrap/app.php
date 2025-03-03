<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Add your global middleware here if needed
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle HTTP exceptions dynamically
        $exceptions->render(function (Throwable $e, Illuminate\Http\Request $request) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $statusCode = $e->getStatusCode();

                // Check if a custom view exists for the specific status code
                if (view()->exists("errors.$statusCode")) {
                    return response()->view("errors.$statusCode", ['exception' => $e], $statusCode);
                }

                // Fallback to Laravel's default error response for HTTP exceptions
                return response()->json([
                    'error' => $e->getMessage(),
                ], $statusCode);
            }

        });
    })
    ->create();
