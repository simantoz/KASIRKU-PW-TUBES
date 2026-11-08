<?php

// Vercel serverless entry point for Laravel

// CRITICAL: Setup temp directories BEFORE anything else
@mkdir('/tmp/views', 0777, true);
@mkdir('/tmp/cache', 0777, true);
@mkdir('/tmp/sessions', 0777, true);
@mkdir('/tmp/framework/cache', 0777, true);
@mkdir('/tmp/framework/sessions', 0777, true);
@mkdir('/tmp/framework/views', 0777, true);

// Create SQLite database if not exists
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);
    chmod($dbPath, 0666);
}

// Set proper working directory
chdir(__DIR__ . '/..');

// Load Laravel
require __DIR__ . '/../public/index.php';
