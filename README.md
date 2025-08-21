# 🎯 Pathfinder - Career Guidance Web Application

A comprehensive career guidance platform built with Laravel that helps users discover their ideal career path, visualize their journey, and bridge skill gaps.

## ✨ Features

- **Career Guidance System**: Interactive questionnaires for personalized course and job recommendations
- **Career Path Visualizer**: Step-by-step roadmap from current position to target role
- **Skill Gap Analyzer**: Detailed analysis of current vs required skills with learning priorities
- **Modern UI/UX**: Responsive design with Tailwind CSS
- **Native Laravel**: Uses Laravel Mix for asset compilation (no Vite dependency)

## 🔧 System Requirements

### Required Software
- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 16.x or higher
- **NPM**: 8.x or higher
- **MySQL**: 8.0 or higher (or compatible database)
- **Web Server**: Apache/Nginx (or use Laravel's built-in server)

### PHP Extensions
Ensure these PHP extensions are installed:
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo

## 🚀 Installation & Setup

### 1. Clone or Download the Project
```bash
# If using Git
git clone <repository-url>
cd Pathfinder

# Or extract the downloaded ZIP file and navigate to the Pathfinder directory
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node.js Dependencies
```bash
npm install
```

### 4. Environment Configuration
```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Database Setup

#### Option A: Using MySQL (Recommended)
1. Create a MySQL database named `pathfinder`
2. Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pathfinder
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### Option B: Using SQLite (Alternative)
1. Update your `.env` file:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```
2. Create the SQLite file:
```bash
touch database/database.sqlite
```

### 6. Run Database Migrations
```bash
php artisan migrate
```

### 7. Compile Assets
```bash
# For development
npm run development

# For production
npm run production

# For watching changes during development
npm run watch
```

## 🏃‍♂️ Running the Application

### Method 1: Laravel Development Server (Recommended for Development)
```bash
php artisan serve
```
The application will be available at: `http://127.0.0.1:8000`

### Method 2: Using the Convenient Dev Script
```bash
composer run dev
```
This runs both the Laravel server and queue listener simultaneously.

### Method 3: Production Setup
For production, configure your web server (Apache/Nginx) to point to the `public` directory.

## 📱 Application Usage

### Main Features

1. **Homepage** (`/`)
   - Overview of all features
   - Quick access to main tools

2. **Career Guidance** (`/career-guidance`)
   - Choose between course or job recommendations
   - Take interactive questionnaires
   - Get personalized recommendations

3. **Career Path Visualizer** (`/career-path`)
   - Input current and target roles
   - View step-by-step roadmap
   - See timeline estimates and action items

4. **Skill Gap Analyzer** (`/skill-gap`)
   - Select target role
   - Mark current skills
   - Get detailed gap analysis with priorities

### Navigation
- Use the top navigation bar to switch between features
- Mobile-responsive design works on all devices
- Each feature includes helpful tips and guidance

## 🛠️ Development

### Asset Compilation
```bash
# Development (unminified)
npm run dev

# Production (minified)
npm run production

# Watch for changes
npm run watch

# Hot reloading
npm run hot
```

## Project Structure

This Laravel application follows the standard MVC architecture with additional features for career guidance and tutorial management.

### 📁 Folder Structure

```
Pathfinder/
├── app/                          # Backend Application Logic
│   ├── Http/                     # HTTP Layer (Controllers, Middleware, Requests)
│   │   ├── Controllers/          # Application Controllers
│   │   │   ├── PathfinderController.php    # Main career guidance logic
│   │   │   ├── TutorialController.php      # Tutorial management
│   │   │   └── DashboardController.php     # User dashboard
│   │   └── Middleware/           # Custom middleware
│   ├── Models/                   # Eloquent Models (Database Layer)
│   │   ├── User.php             # User model with progress tracking
│   │   ├── UserProgress.php     # Career assessment progress
│   │   ├── Tutorial.php         # Tutorial content model
│   │   └── UserTutorialProgress.php # Tutorial learning progress
│   ├── Providers/               # Service providers
│   └── View/                    # View composers and creators
│
├── bootstrap/                   # Application Bootstrap
│   ├── app.php                  # Application bootstrap file
│   ├── cache/                   # Bootstrap cache files
│   └── providers.php            # Service provider registration
│
├── config/                      # Configuration Files
│   ├── app.php                  # Main application configuration
│   ├── database.php             # Database configuration
│   ├── auth.php                 # Authentication configuration
│   └── session.php              # Session configuration
│
├── database/                    # Database Layer
│   ├── migrations/              # Database schema migrations
│   │   ├── create_users_table.php          # User authentication tables
│   │   ├── create_user_progress_table.php  # Career progress tracking
│   │   ├── create_tutorials_table.php      # Tutorial content
│   │   └── create_user_tutorial_progress_table.php # Learning progress
│   ├── seeders/                 # Database seeders
│   │   └── TutorialSeeder.php   # Pre-populate tutorial content
│   └── factories/               # Model factories for testing
│
├── public/                      # Web Server Document Root
│   ├── index.php               # Application entry point
│   ├── css/                    # Compiled CSS files
│   ├── js/                     # Compiled JavaScript files
│   ├── build/                  # Laravel Mix build assets
│   └── mix-manifest.json       # Asset manifest for cache busting
│
├── resources/                   # Frontend Resources
│   ├── views/                   # Blade Templates (Frontend UI)
│   │   ├── pathfinder/         # Main application views
│   │   │   ├── layout.blade.php           # Main layout template
│   │   │   ├── index.blade.php            # Homepage
│   │   │   ├── career-guidance.blade.php  # Career assessment
│   │   │   ├── questionnaire.blade.php    # Assessment questionnaire
│   │   │   ├── recommendation.blade.php   # Assessment results
│   │   │   ├── career-path.blade.php      # Career path planner
│   │   │   ├── career-path-result.blade.php # Path visualization
│   │   │   ├── skill-gap.blade.php        # Skill analysis
│   │   │   └── skill-gap-result.blade.php # Skill gap results with tutorials
│   │   ├── tutorials/          # Tutorial management views
│   │   │   └── index.blade.php            # Tutorial dashboard
│   │   ├── auth/               # Authentication views
│   │   │   ├── login.blade.php            # Custom login page
│   │   │   └── register.blade.php         # Custom registration page
│   │   └── dashboard.blade.php # User dashboard
│   ├── css/                    # Source CSS files
│   │   └── app.css             # Main stylesheet (Tailwind CSS)
│   └── js/                     # Source JavaScript files
│       ├── app.js              # Main JavaScript entry point
│       └── bootstrap.js        # JavaScript bootstrap
│
├── routes/                      # Application Routes
│   ├── web.php                 # Web routes (main application)
│   ├── auth.php                # Authentication routes
│   └── console.php             # Artisan console routes
│
├── storage/                     # Application Storage
│   ├── app/                    # Application files
│   ├── framework/              # Framework cache and sessions
│   └── logs/                   # Application logs
│
├── tests/                       # Automated Tests
│   ├── Feature/                # Feature tests
│   ├── Unit/                   # Unit tests
│   └── TestCase.php            # Base test case
│
├── vendor/                      # Composer Dependencies
│   └── ...                     # Third-party packages
│
├── .env                        # Environment Configuration
├── .env.example               # Environment template
├── composer.json              # PHP dependencies
├── package.json               # Node.js dependencies
├── webpack.mix.js             # Laravel Mix configuration
├── tailwind.config.js         # Tailwind CSS configuration
└── README.md                  # Project documentation
```

### 🎯 Key Components

**Frontend (User Interface)**
- `resources/views/` - All user-facing templates and UI components
- `resources/css/` - Styling with Tailwind CSS framework
- `resources/js/` - Interactive JavaScript functionality
- `public/` - Compiled assets served to users

**Backend (Server Logic)**
- `app/Http/Controllers/` - Business logic and request handling
- `app/Models/` - Data models and database interactions
- `database/` - Database schema and data management
- `routes/` - URL routing and endpoint definitions

**Configuration & Setup**
- `config/` - Application settings and configurations
- `.env` - Environment-specific settings (database, keys)
- `composer.json` - PHP package management
- `package.json` - Frontend asset management

## 🔧 Configuration

### Laravel Mix (webpack.mix.js)
The application uses Laravel Mix for asset compilation:
- CSS: Tailwind CSS with PostCSS
- JavaScript: ES6+ with Babel
- Asset versioning for production

### Tailwind CSS (tailwind.config.js)
Custom Tailwind configuration with:
- Custom font family (Instrument Sans)
- Responsive breakpoints
- Content paths for purging

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Verify MySQL is running
   - Check database credentials in `.env`
   - Ensure database exists

2. **Assets Not Loading**
   - Run `npm run development`
   - Check if `public/css/app.css` and `public/js/app.js` exist
   - Clear browser cache

3. **Permission Errors**
   - Ensure `storage/` and `bootstrap/cache/` are writable
   - Run: `chmod -R 775 storage bootstrap/cache`

4. **Composer Dependencies**
   - Run `composer install` if vendor folder is missing
   - Update with `composer update`

5. **Node Dependencies**
   - Delete `node_modules` and run `npm install`
   - Clear npm cache: `npm cache clean --force`

### Performance Optimization

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

## 📝 Additional Notes

- The application uses mock data for demonstrations
- All features are fully functional with sample algorithms
- Responsive design tested on desktop, tablet, and mobile
- Built with Laravel 12.x and modern web standards

## 🤝 Support

If you encounter any issues:
1. Check the troubleshooting section above
2. Verify all requirements are met
3. Ensure all installation steps were followed
4. Check Laravel logs in `storage/logs/`

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
