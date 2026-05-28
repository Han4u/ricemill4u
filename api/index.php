<?php

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Automatically run migrations on Vercel if SQLite database is empty/unmigrated
if (isset($_ENV['VERCEL']) && env('DB_CONNECTION', 'sqlite') === 'sqlite') {
    $dbPath = env('DB_DATABASE');
    if ($dbPath && file_exists($dbPath)) {
        try {
            $pdo = new PDO("sqlite:$dbPath");
            $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
            $hasUsersTable = $result && $result->fetch();
            $pdo = null; // Close the connection to release file lock

            if (!$hasUsersTable) {
                // Resolve console kernel to run migrations
                $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
                $kernel->call('migrate', ['--force' => true]);
            }
        } catch (\Exception $e) {
            // Silence exceptions to avoid blocking request if database is not ready
        }
    }
}

// Handle the HTTP request...
use Illuminate\Http\Request;
$app->handleRequest(Request::capture());
