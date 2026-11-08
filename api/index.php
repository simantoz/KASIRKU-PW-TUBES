<?php

// Vercel serverless entry point for Laravel

// Set proper working directory
chdir(__DIR__ . '/..');

// Debug mode for initial deploy
if (!getenv('APP_KEY')) {
    http_response_code(500);
    echo json_encode([
        'error' => 'APP_KEY not set',
        'message' => 'Add APP_KEY environment variable in Vercel dashboard',
        'value' => 'base64:pKdzMxL2BapZbTpMhTM2hvdA7DR4WzF6HGjM3RpDhJg='
    ]);
    exit;
}

// Ensure vendor autoload exists
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Composer dependencies not installed',
        'message' => 'Run: composer install'
    ]);
    exit;
}

// Ensure tmp directories exist
@mkdir('/tmp/views', 0755, true);
@mkdir('/tmp/cache', 0755, true);

// Create SQLite database if not exists
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);
    chmod($dbPath, 0666);
}

// Load Laravel
require __DIR__ . '/../public/index.php';
