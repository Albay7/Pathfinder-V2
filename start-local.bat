@echo off
echo Starting Pathfinder with Local Database...
echo.
echo Using local database: pathfinder_local
echo Database host: 127.0.0.1:3306
echo.

REM Backup original environment file
copy .env .env.backup

REM Copy local environment file to .env
copy .env.local .env

REM Start the Laravel development server with local environment
php artisan serve

REM Restore original environment file
copy .env.backup .env
del .env.backup

pause