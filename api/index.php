<?php

// Vercel serverless entry point for Laravel

// Enable error display
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Setup temp directories
@mkdir('/tmp/views', 0777, true);
@mkdir('/tmp/cache', 0777, true);
@mkdir('/tmp/sessions', 0777, true);

// Create SQLite database
$dbPath = '/tmp/database.sqlite';
$isNewDatabase = !file_exists($dbPath);

if ($isNewDatabase) {
    touch($dbPath);
    chmod($dbPath, 0666);
}

// Change working directory
chdir(__DIR__ . '/..');

// Auto-migrate & seed on new database
if ($isNewDatabase) {
    exec('php artisan migrate --force 2>&1', $output);
    exec('php artisan db:seed --force 2>&1', $output);
}

// Catch and display errors
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    echo '<pre>';
    echo "ERROR: " . $e->getMessage() . "\n\n";
    echo "FILE: " . $e->getFile() . "\n";
    echo "LINE: " . $e->getLine() . "\n\n";
    echo "TRACE:\n" . $e->getTraceAsString();
    echo '</pre>';
    exit;
}
