<?php

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\CheckRoleStatus; // Import middleware Anda
use App\Http\Middleware\RoleMiddleware;

// use Illuminate\Middleware\Middleware;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'checkRoleStatus' => CheckRoleStatus::class,
            'checkrole' => CheckRole::class,
            'permission' => CheckPermission::class,

        ]);
    }
    
    )

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();



    // return Application::configure()
    // ->withMiddleware(function (Middleware $middleware) {
    //     $middleware->append([
    //         CheckRoleStatus::class,
    //     ]);
    // });



    // $app->withMiddleware(function (Middleware $middleware) {
    //     $middleware->append(CheckRoleStatus::class);
    // });