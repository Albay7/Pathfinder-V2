<?php

// Generate a new application key
$appKey = 'base64:' . base64_encode(random_bytes(32));

// Database configuration - replace with your actual values
$dbHost = 'your-db-host.com';
$dbName = 'pathfinder_db';
$dbUser = 'db_username';
$dbPass = 'db_password';

// Output the commands to set environment variables in Vercel
echo "=== Vercel Environment Variables ===\n\n";
echo "# Run these commands to set up your Vercel environment:\n\n";
echo "vercel env add APP_KEY \"$appKey\"\n";
echo "vercel env add APP_ENV \"production\"\n";
echo "vercel env add APP_DEBUG \"false\"\n";
echo "vercel env add APP_URL \"https://pathfinder-v2-zm1l-git-main-chasing-haze-exias-projects.vercel.app\"\n";
echo "vercel env add DB_CONNECTION \"mysql\"\n";
echo "vercel env add DB_HOST \"$dbHost\"\n";
echo "vercel env add DB_PORT \"3306\"\n";
echo "vercel env add DB_DATABASE \"$dbName\"\n";
echo "vercel env add DB_USERNAME \"$dbUser\"\n";
echo "vercel env add DB_PASSWORD \"$dbPass\"\n";

echo "\n=== For GitHub Secrets ===\n\n";
echo "VERCEL_TOKEN: [Your Vercel API token]\n";
echo "DB_HOST: $dbHost\n";
echo "DB_DATABASE: $dbName\n";
echo "DB_USERNAME: $dbUser\n";
echo "DB_PASSWORD: $dbPass\n";

echo "\n=== Manual Setup in Vercel Dashboard ===\n\n";
echo "1. Go to https://vercel.com/dashboard\n";
echo "2. Select your project\n";
echo "3. Go to Settings > Environment Variables\n";
echo "4. Add the following variables:\n";
echo "   - APP_KEY: $appKey\n";
echo "   - APP_ENV: production\n";
echo "   - APP_DEBUG: false\n";
echo "   - APP_URL: https://pathfinder-v2-zm1l-git-main-chasing-haze-exias-projects.vercel.app\n";
echo "   - DB_CONNECTION: mysql\n";
echo "   - DB_HOST: $dbHost\n";
echo "   - DB_PORT: 3306\n";
echo "   - DB_DATABASE: $dbName\n";
echo "   - DB_USERNAME: $dbUser\n";
echo "   - DB_PASSWORD: $dbPass\n";