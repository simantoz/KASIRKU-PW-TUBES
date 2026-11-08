<?php

// Vercel serverless entry point for Laravel

// CRITICAL: Setup temp directories BEFORE anything else
@mkdir('/tmp/framework/cache', 0777, true);
@mkdir('/tmp/framework/sessions', 0777, true);
@mkdir('/tmp/framework/views', 0777, true);
@mkdir('/tmp/bootstrap/cache', 0777, true);

// Create SQLite database if not exists
$dbPath = '/tmp/database.sqlite';
$isNewDatabase = !file_exists($dbPath);

if ($isNewDatabase) {
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

// Auto-migrate and seed on new database
if ($isNewDatabase) {
    // Run migrations
    exec('php artisan migrate --force 2>&1', $output, $return);
    
    // Run seeder
    exec('php artisan db:seed --force 2>&1', $output, $return);
}

// Load Laravel
require __DIR__ . '/../public/index.php';
