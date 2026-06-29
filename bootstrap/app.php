<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureAdminPanelAccess;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        $middleware->alias([
            'permission' => CheckPermission::class,
            'auth' => Authenticate::class,
            'role' => CheckRole::class,
            'admin.panel' => EnsureAdminPanelAccess::class,
        ]);

        $middleware->prepend(\App\Http\Middleware\UseTenantByDomain::class);
        $middleware->append(\App\Http\Middleware\TrustProxies::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ValidationException $exception, Request $request) {
            if (! $request->expectsJson() && ! str_starts_with($request->getHost(), 'api.')) {
                return null;
            }

            return response()->json([
                'success' => false,
                'code' => 'VALIDATION_ERROR',
                'message' => 'Dữ liệu chưa hợp lệ.',
                'errors' => $exception->errors(),
            ], 422);
        });

        $exceptions->render(function (TooManyRequestsHttpException $exception, Request $request) {
            if (! $request->expectsJson() && ! str_starts_with($request->getHost(), 'api.')) {
                return null;
            }

            return response()->json([
                'success' => false,
                'code' => 'TOO_MANY_REQUESTS',
                'message' => 'Bạn đã gửi quá nhiều yêu cầu. Vui lòng chờ một chút rồi thử lại.',
            ], 429);
        });
    })->create();
