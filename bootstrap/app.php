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

    // Automatically detect Vercel Postgres database if available (prefer non-pooling/unpooled for migrations)
    $postgresUrl = env('POSTGRES_URL_NON_POOLING') ?: env('DATABASE_URL_UNPOOLED') ?: env('POSTGRES_URL') ?: env('DATABASE_URL');
    if ($postgresUrl) {
        putenv("DB_CONNECTION=pgsql");
        $_ENV['DB_CONNECTION'] = 'pgsql';
        $_SERVER['DB_CONNECTION'] = 'pgsql';

        putenv("DB_URL={$postgresUrl}");
        $_ENV['DB_URL'] = $postgresUrl;
        $_SERVER['DB_URL'] = $postgresUrl;
    }
    // SQLite writable workaround
    elseif (env('DB_CONNECTION', 'sqlite') === 'sqlite') {
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
        // Instead of calling config(), set environment variables directly
        putenv("DB_DATABASE={$dbPath}");
        $_ENV['DB_DATABASE'] = $dbPath;
        $_SERVER['DB_DATABASE'] = $dbPath;
    }
}

return $app;