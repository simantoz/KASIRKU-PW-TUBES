<?php

// Vercel serverless entry point for Laravel

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

// Load Laravel
require __DIR__ . '/../public/index.php';
