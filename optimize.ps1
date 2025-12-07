# Vetlio Performance Optimization Script
# Run this script to optimize the application for better performance

Write-Host "Starting Vetlio optimization..." -ForegroundColor Green

# Clear all caches
Write-Host "`nClearing caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cache components
Write-Host "`nCaching Filament components..." -ForegroundColor Yellow
php artisan filament:cache-components

# Cache configuration and routes (production-level)
Write-Host "`nCaching configuration, routes, and views..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
Write-Host "`nOptimizing autoloader..." -ForegroundColor Yellow
composer dump-autoload -o

Write-Host "`nOptimization complete!" -ForegroundColor Green
Write-Host "Your application should now load faster." -ForegroundColor Cyan
