# Pathfinder App - Vercel Deployment Guide

## Database Setup

1. Create a MySQL database for your application. You can use:
   - [PlanetScale](https://planetscale.com/) (Free tier available)
   - [Railway MySQL](https://railway.app/new/mysql) (Free trial available)
   - [Supabase PostgreSQL](https://supabase.com/) (Free tier available)
   - [Neon PostgreSQL](https://neon.tech/) (Free tier available)

2. Get your database connection details:
   - Host
   - Port (usually 3306 for MySQL)
   - Database name
   - Username
   - Password

## Vercel Deployment

1. Push your code to GitHub if you haven't already.

2. Go to [Vercel Dashboard](https://vercel.com/dashboard) and click "Add New" > "Project".

3. Import your GitHub repository.

4. Configure the project:
   - Framework Preset: Laravel
   - Build Command: `composer install --no-dev && npm install && npm run build`
   - Output Directory: `public`

5. Add the following environment variables:
   - `APP_KEY`: Generate with `php artisan key:generate --show`
   - `DB_HOST`: Your database host
   - `DB_PORT`: Your database port (usually 3306)
   - `DB_DATABASE`: Your database name
   - `DB_USERNAME`: Your database username
   - `DB_PASSWORD`: Your database password

6. Click "Deploy" and wait for the deployment to complete.

7. After deployment, run database migrations by connecting to Vercel CLI:
   ```
   vercel env pull .env.production.local
   vercel --prod
   ```

8. Your app should now be running with a complete database setup!

## Troubleshooting

If you encounter any issues:
1. Check Vercel deployment logs
2. Verify database connection details
3. Ensure your database allows connections from Vercel's IP addresses
4. Check if your database requires SSL connection

## Automatic Updates

Your app will automatically update when you push changes to your GitHub repository.