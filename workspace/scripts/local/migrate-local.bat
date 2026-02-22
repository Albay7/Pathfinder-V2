@echo off
echo Running migrations on Local Database...
echo.
echo Database: pathfinder_local
echo Host: 127.0.0.1:3306
echo.

REM Backup current .env
copy .env .env.backup

REM Use local environment
copy .env.local .env

REM Run migrations
php artisan migrate --force

REM Restore original .env
copy .env.backup .env
del .env.backup

echo.
echo Local database migrations completed!
pause