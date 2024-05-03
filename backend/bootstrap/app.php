<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\ForceJson;
use App\Http\Middleware\OwnCors;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(callback: function (Middleware $middleware) {
        $middleware->group('web', [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            ValidateCsrfToken::class,
            SubstituteBindings::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
        ]);

        $middleware->group('api', [
            ForceJson::class,
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // 'throttle:api',
            SubstituteBindings::class,
            OwnCors::class,
        ]);

        $middleware->alias([
            'verified' => EnsureEmailIsVerified::class,
            'auth' => Authenticate::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e) {
            $errors = collect($e->errors())->map(fn($error, $key) => [
                'title' => 'The given data was invalid.',
                'detail' => $error[0],
                'source' => [
                    'pointer' => '/' . Str::of($key)->replace('.', '/')->value(),
                ],
            ])->values();

            return response()->json(
                data: [
                    'errors' => $errors,
                ],
                status: $e->status,
                headers: [
                    'Content-Type' => 'application/json',
                ]
            );
        });

        $exceptions->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'errors' => [
                    [
                        'title' => 'Oops! Something went wrong.',
                        'detail' => empty($e->getMessage()) ? 'Verifica el status code e intentanuevamente' : $e->getMessage(),
                    ],
                ],
            ], $e->getStatusCode());
        });

        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    [
                        'title' => 'Unauthenticated',
                        'detail' => 'You are not authenticated,check your credentials and try again.',
                    ],
                ],
            ], 401);
        });

        $exceptions->render(function (QueryException $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    [
                        'title' => 'Database error',
                        'detail' => 'There was an error with the database, please try again later.',
                    ],
                ],
            ], 500);
        });
    })->create();
