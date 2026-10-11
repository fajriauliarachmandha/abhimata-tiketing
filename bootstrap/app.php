<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
     ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);

        $middleware->alias([
            'it_support' => \App\Http\Middleware\EnsureIsItSupport::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->shouldRenderJsonWhen(
        fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
    );

    $exceptions->respond(function (
        \Symfony\Component\HttpFoundation\Response $response,
        \Throwable $exception,
        Request $request,
    ) {
        // Selain 404 atau permintaan JSON/API, biarkan seperti biasa
        if (
            $response->getStatusCode() !== 404
            || $request->expectsJson()
            || $request->is('api/*')
        ) {
            return $response;
        }

        try {
            return \Inertia\Inertia::render('Errors/NotFound')
                ->toResponse($request)
                ->setStatusCode(404);
        } catch (\Throwable $e) {
            report($e); // penyebab error masuk ke storage/logs/laravel.log
            return $response;
        }
    });
    })->create();
