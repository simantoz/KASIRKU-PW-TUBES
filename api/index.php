<?php

// Vercel serverless entry point for Laravel

// Set proper working directory
chdir(__DIR__ . '/..');

// Ensure vendor autoload exists
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    http_response_code(500);
    die('Composer dependencies not installed. Run: composer install');
}

// Load Laravel
require __DIR__ . '/../public/index.php';
