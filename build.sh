#!/bin/bash

# Vercel Build Script for Laravel
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
npm ci
npm run build
