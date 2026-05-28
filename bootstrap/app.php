<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

if (isset($_ENV['VERCEL'])) {
    $app->useStoragePath('/tmp');

    // SQLite writable workaround
    if (env('DB_CONNECTION', 'sqlite') === 'sqlite') {
        $dbPath = '/tmp/database.sqlite';
        if (!file_exists($dbPath)) {
            $originalDb = base_path('database/database.sqlite');
            if (file_exists($originalDb)) {
                copy($originalDb, $dbPath);
            } else {
                if (!file_exists('/tmp')) {
                    mkdir('/tmp', 0755, true);
                }
                touch($dbPath);
            }
        }
        config(['database.connections.sqlite.database' => $dbPath]);
    }
}

return $app;