<?php

// Vercel serverless entry point for Laravel

// CRITICAL: Setup temp directories BEFORE anything else
@mkdir('/tmp/framework/cache', 0777, true);
@mkdir('/tmp/framework/sessions', 0777, true);
@mkdir('/tmp/framework/views', 0777, true);
@mkdir('/tmp/bootstrap/cache', 0777, true);

// Create SQLite database if not exists
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);
    chmod($dbPath, 0666);
}

// Set proper working directory
chdir(__DIR__ . '/..');

// Clear any bootstrap cache that might interfere
$cacheFiles = [
    __DIR__ . '/../bootstrap/cache/config.php',
    __DIR__ . '/../bootstrap/cache/routes-v7.php',
    __DIR__ . '/../bootstrap/cache/services.php',
    __DIR__ . '/../bootstrap/cache/packages.php',
    __DIR__ . '/../bootstrap/cache/events.php',
];

foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        @unlink($file);
    }
}

// Enable error display for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Catch all errors and display them
try {
    // Check autoload
    if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
        http_response_code(500);
        die(json_encode([
            'error' => 'Composer autoload not found',
            'path' => __DIR__ . '/../vendor/autoload.php',
            'cwd' => getcwd()
        ]));
    }
    
    // Load Laravel
    require __DIR__ . '/../public/index.php';
    
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Laravel Boot Failed',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => array_slice($e->getTrace(), 0, 5)
    ], JSON_PRETTY_PRINT);
    exit;
}
