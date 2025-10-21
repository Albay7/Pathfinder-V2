# 🎯 Pathfinder - Advanced Career Guidance Platform

A comprehensive, intelligent career guidance platform built with Laravel that helps users discover their ideal career path through dynamic assessments, personalized recommendations, and structured learning paths.

## ✨ Key Features

### 🎓 **Intelligent Assessment System**
- **Dynamic Course Assessment**: 7-question branching questionnaire with field-specific questions for DLSU Dasmariñas programs
- **Career-Focused Job Assessment**: Separate 7-question assessment focused on career goals and professional development
- **Industry-Specific Branching**: Questions adapt based on user's field/industry selection (9 industries covered)
- **Smart Recommendation Engine**: Multi-factor scoring algorithm with weighted preferences
- **Category-Based Recommendation Algorithm**: Dynamic scoring system that processes questionnaire responses based on selected category (Business, IT, Creative, Law, Tourism)
- **Real-time Score Calculation**: JavaScript-based computation of compatibility scores for both courses and jobs
- **Fallback Recommendation System**: Category-specific fallback recommendations when no clear match is found

### 🏢 **Comprehensive Coverage**
- **DLSU Dasmariñas Integration**: All 9 colleges covered with specific course recommendations
- **90+ Job Titles**: Extensive job database across Technology, Business, Finance, Healthcare, Education, Marketing, Engineering, Government, and Tourism
- **Field-Specific Questions**: Tailored questions for each academic field and industry

### 📚 **Tutorial & Learning System**
- **Skill-Based Tutorials**: Curated learning resources for skill development
- **Progress Tracking**: Monitor learning progress and completion rates
- **Integrated Recommendations**: Tutorials linked to skill gap analysis results

### 🛤️ **Career Planning Tools**
- **Career Path Visualizer**: Step-by-step roadmap from current position to target role
- **Skill Gap Analyzer**: Detailed analysis with tutorial recommendations and learning priorities
- **Progress Dashboard**: Track assessment history and learning journey

### 🔐 **User Management**
- **Authentication System**: Secure user registration and login
- **Progress Persistence**: Save assessment results and learning progress
- **Personalized Dashboard**: Individual user experience with history tracking

### 🎨 **Modern User Experience**
- **Responsive Design**: Optimized for desktop, tablet, and mobile devices
- **Dynamic UI**: Interactive questionnaires with real-time progress tracking
- **Professional Styling**: Clean, modern interface with Tailwind CSS

## 💻 Technical Implementation

### 🧠 **Recommendation Algorithm**
- **Dynamic Category Processing**: Processes questionnaire responses based on the selected category (Business, IT, Creative, Law, Tourism)
- **Score-Based Matching**: Calculates compatibility scores for each course/job based on weighted question responses
- **Client-Side Computation**: Initial scoring performed in JavaScript for immediate feedback
- **Server-Side Validation**: Final recommendations validated and processed on the server
- **Fallback System**: Category-specific default recommendations when user responses don't yield clear matches

### 🗄️ **Database Structure**
- **Questionnaire System**: Tables for questionnaires, questions, and user responses
- **Recommendation Storage**: Separate tables for course and job recommendations
- **User Progress Tracking**: Persistent storage of assessment results and learning progress
- **Migrations & Seeders**: Comprehensive database setup with pre-populated course and job data

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

### 🏠 **Homepage** (`/`)
- **Feature Overview**: Comprehensive introduction to all platform capabilities
- **Quick Access**: Direct links to assessments and tools
- **Modern Design**: Professional landing page with clear navigation

### 🎓 **Career Guidance System** (`/career-guidance`)
- **Dual Assessment Options**: Choose between Course or Job recommendations
- **Course Assessment**: 7-question dynamic questionnaire for DLSU Dasmariñas program recommendations
- **Job Assessment**: Career-focused questionnaire for professional opportunities
- **Smart Results**: Personalized recommendations with detailed explanations

### 📋 **Dynamic Questionnaires**
- **Adaptive Questions**: Questions change based on your field/industry selection
- **Progress Tracking**: Real-time progress bar and question counter
- **Field-Specific Branching**:
  - **Course Assessment**: Engineering, Computer Science, Business, Education, Accounting, Liberal Arts, Tourism, Science, Criminal Justice
  - **Job Assessment**: Technology, Business, Finance, Healthcare, Education, Marketing, Engineering, Government, Tourism
- **Intelligent Scoring**: Multi-factor algorithm considering interests, skills, and preferences

### 🛤️ **Career Path Visualizer** (`/career-path`)
- **Role Mapping**: Input current position and target career goal
- **Step-by-Step Roadmap**: Detailed progression path with actionable steps
- **Timeline Estimates**: Realistic timeframes for career transitions
- **Skill Requirements**: Clear breakdown of skills needed for advancement

### 📊 **Skill Gap Analyzer** (`/skill-gap`)
- **Comprehensive Analysis**: Compare current skills with target role requirements
- **Priority Matrix**: Skills ranked by importance and difficulty
- **Tutorial Integration**: Direct links to relevant learning resources
- **Progress Tracking**: Monitor skill development over time

### 📚 **Tutorial System**
- **Curated Content**: High-quality learning resources for skill development
- **Progress Tracking**: Monitor completion rates and learning milestones
- **Skill-Based Organization**: Tutorials organized by skill categories
- **Integrated Recommendations**: Seamlessly linked to skill gap analysis

### 🔐 **User Authentication & Dashboard**
- **Secure Registration/Login**: Protected user accounts with progress persistence
- **Personal Dashboard**: Track assessment history and learning progress
- **Progress Analytics**: Visual representation of career development journey
- **Recommendation History**: Access to all previous assessment results

### 📱 **Navigation & Accessibility**
- **Responsive Design**: Optimized experience across desktop, tablet, and mobile
- **Intuitive Interface**: Clear navigation with breadcrumbs and progress indicators
- **Accessibility Features**: Screen reader compatible and keyboard navigation
- **Modern UX**: Smooth transitions and interactive elements

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
│   │   │   ├── PathfinderController.php    # Main career guidance with smart algorithms
│   │   │   ├── TutorialController.php      # Tutorial management and progress
│   │   │   ├── DashboardController.php     # User dashboard and analytics
│   │   │   └── Auth/            # Authentication controllers
│   │   └── Middleware/           # Custom middleware and authentication
│   ├── Models/                   # Eloquent Models (Database Layer)
│   │   ├── User.php             # User model with authentication and progress
│   │   ├── UserProgress.php     # Assessment results and career progress
│   │   ├── Tutorial.php         # Tutorial content with skill categorization
│   │   └── UserTutorialProgress.php # Learning progress and completion tracking
│   ├── Providers/               # Service providers and application bootstrapping
│   └── View/                    # View composers and data binding
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
│   │   │   ├── layout.blade.php           # Main responsive layout with navigation
│   │   │   ├── index.blade.php            # Enhanced homepage with feature overview
│   │   │   ├── career-guidance.blade.php  # Dual assessment selection (Course/Job)
│   │   │   ├── questionnaire.blade.php    # Dynamic branching questionnaire system
│   │   │   ├── recommendation.blade.php   # Smart recommendation results with explanations
│   │   │   ├── career-path.blade.php      # Interactive career path planner
│   │   │   ├── career-path-result.blade.php # Visual roadmap with timeline
│   │   │   ├── skill-gap.blade.php        # Comprehensive skill analysis tool
│   │   │   └── skill-gap-result.blade.php # Detailed gap analysis with tutorial links
│   │   ├── tutorials/          # Tutorial management and progress views
│   │   │   ├── index.blade.php            # Tutorial dashboard with progress tracking
│   │   │   └── show.blade.php             # Individual tutorial content view
│   │   ├── auth/               # Authentication and user management
│   │   │   ├── login.blade.php            # Responsive login with validation
│   │   │   ├── register.blade.php         # User registration with progress setup
│   │   │   └── verify-email.blade.php     # Email verification page
│   │   └── dashboard.blade.php # Personalized user dashboard with analytics
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

## 🧠 Technical Implementation

### 🎯 **Smart Recommendation Algorithms**

**Course Recommendation Engine:**
- **Multi-factor Scoring**: Weighted algorithm considering field interest (40%), academic strengths (30%), career vision (20%), and program alignment (10%)
- **DLSU Integration**: Comprehensive mapping of all 9 colleges with specific course offerings
- **Dynamic Scoring**: Real-time calculation based on user responses

**Job Recommendation Engine:**
- **Industry-Specific Database**: 90+ job titles across 9 major industries
- **Career Goal Alignment**: Scoring based on career objectives (entry-level, advancement, entrepreneurship)
- **Responsibility Matching**: Algorithm considers preferred job responsibilities and work styles
- **Experience-Aware**: Recommendations adapt to user's career stage and goals

### 🔀 **Dynamic Questionnaire System**

**Branching Logic:**
- **JavaScript-Powered**: Real-time question adaptation based on user selections
- **Field-Specific Questions**: 2 additional questions per field/industry (18 total variations)
- **Progress Tracking**: Dynamic progress bar that adapts to question flow
- **Validation System**: Ensures all questions are answered before progression

**Question Categories:**
- **Course Assessment**: 9 academic fields with specialized questions
- **Job Assessment**: 9 industries with career-focused questions
- **Adaptive Flow**: Questions 5-6 change based on Question 2 selection

### 📊 **Data Management**

**User Progress Tracking:**
- **Assessment History**: Persistent storage of all assessment results
- **Tutorial Progress**: Completion tracking with percentage calculations
- **Dashboard Analytics**: Visual representation of learning journey

**Database Architecture:**
- **Normalized Schema**: Efficient data storage with proper relationships
- **Progress Models**: Separate tracking for assessments and tutorials
- **User Authentication**: Secure session management with Laravel Sanctum

### 🎨 **Frontend Architecture**

**Responsive Design:**
- **Mobile-First**: Optimized for all device sizes
- **Progressive Enhancement**: Works without JavaScript, enhanced with it
- **Accessibility**: WCAG 2.1 compliant with screen reader support

**Interactive Elements:**
- **Real-time Validation**: Immediate feedback on form inputs
- **Smooth Transitions**: CSS animations for better user experience
- **Dynamic Content**: AJAX-powered updates without page reloads

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

## 💾 Database Structure

The application uses the following database tables:

### Core Tables

#### `users`
- `id` - Primary key
- `name` - User's full name
- `email` - Unique email for authentication
- `email_verified_at` - Timestamp for email verification
- `password` - Hashed password
- `remember_token` - Token for "remember me" functionality
- `timestamps` - Created and updated timestamps

#### `user_progress`
- `id` - Primary key
- `user_id` - Foreign key to users table
- `feature_type` - Type of feature ('career_guidance', 'career_path', 'skill_gap')
- `assessment_type` - Type of assessment ('course', 'job')
- `questionnaire_answers` - JSON storage of questionnaire responses
- `recommendation` - Stored recommendation result
- `current_role` - User's current role (for career path)
- `target_role` - User's target role (for career path and skill gap)
- `current_skills` - JSON storage of user's current skills (for skill gap)
- `analysis_result` - JSON storage of complete analysis results
- `match_percentage` - Skill gap match percentage
- `completed` - Whether the assessment was completed
- `timestamps` - Created and updated timestamps

### Tutorial System Tables

#### `tutorials`
- `id` - Primary key
- `title` - Tutorial title
- `description` - Tutorial description
- `skill` - The skill this tutorial teaches
- `level` - Difficulty level ('beginner', 'intermediate', 'advanced')
- `type` - Content type ('video', 'article', 'course', 'documentation')
- `url` - Tutorial link
- `provider` - Content provider (YouTube, Coursera, etc.)
- `duration_minutes` - Estimated duration
- `rating` - User rating (out of 5)
- `difficulty` - Difficulty scale (1-5)
- `prerequisites` - JSON storage of required skills/knowledge
- `tags` - JSON storage of additional tags
- `is_free` - Whether the tutorial is free
- `is_active` - Whether the tutorial is active
- `timestamps` - Created and updated timestamps

#### `user_tutorial_progress`
- `id` - Primary key
- `user_id` - Foreign key to users table
- `tutorial_id` - Foreign key to tutorials table
- `status` - Progress status ('not_started', 'in_progress', 'completed', 'bookmarked')
- `progress_percentage` - Progress percentage (0-100)
- `started_at` - Timestamp when tutorial was started
- `completed_at` - Timestamp when tutorial was completed
- `time_spent_minutes` - Time spent on tutorial
- `user_rating` - User's rating of the tutorial
- `notes` - User's personal notes
- `bookmarks` - JSON storage of specific timestamps or sections bookmarked
- `timestamps` - Created and updated timestamps

### Laravel System Tables

#### `password_reset_tokens`
- `email` - Primary key, user's email
- `token` - Password reset token
- `created_at` - Timestamp when token was created

#### `sessions`
- `id` - Primary key, session ID
- `user_id` - Foreign key to users table
- `ip_address` - User's IP address
- `user_agent` - User's browser information
- `payload` - Session data
- `last_activity` - Timestamp of last activity

#### `cache` and `cache_locks`
- System tables for Laravel's caching functionality

#### `jobs`, `job_batches`, and `failed_jobs`
- System tables for Laravel's queue functionality

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

## ☁️ Azure Cloud Infrastructure & Deployment

### Architecture Overview

Provisioned (via Bicep) Azure resources (parameterized per environment):

- App Service (Linux, PHP 8.2) + optional staging slot
- App Service Plan (Basic tier default)
- MySQL Flexible Server (Burstable B1ms, storage auto-grow)
- Storage Account (Blob private container for Laravel assets/backups)
- Key Vault (RBAC, secret references in App Service application settings)
- Application Insights + Log Analytics (centralized telemetry)
- (Optional) Azure Cache for Redis (feature flag `enableRedis`)
- Virtual Network + subnets (placeholder for future private endpoints)
- Azure ML Workspace (basic SKU for model experimentation)

### Infrastructure as Code

`infra/main.bicep` orchestrates modular resources in `infra/modules/`:

```text
modules/
   appservice.bicep
   appserviceplan.bicep
   mysql.bicep
   storage.bicep
   keyvault.bicep
   insights.bicep
   redis.bicep
   vnet.bicep
   mlworkspace.bicep
parameters/
   dev.json
   prod.json
```

Deploy (GitHub Actions):

```text
Manual:  GitHub > Actions > Infra Deploy > Run workflow (env dev|prod)
Auto:    Push to main affecting infra/** triggers dev deploy (default)
```

Local validation (optional):

```bash
az deployment sub what-if \
   --location southeastasia \
   --template-file infra/main.bicep \
   --parameters @infra/parameters/dev.json
```

### Required GitHub Secrets

Set in repository settings → Secrets and variables → Actions:

- `AZURE_CLIENT_ID` (Federated Workload Identity service principal)
- `AZURE_TENANT_ID`
- `AZURE_SUBSCRIPTION_ID`

Additional (new):

- `MYSQL_ADMIN_PASSWORD` (secure password supplied to Bicep; not stored in parameter files)

App runtime secrets (store in Key Vault, not in GitHub):

- `APP-KEY`, `DB-HOST`, `DB-NAME`, `DB-USER`, `DB-PASSWORD`
- `REDIS-HOST`, `REDIS-PASSWORD` (if Redis enabled)
- `ML-ENDPOINT-URL`, `ML-ENDPOINT-KEY` (after model deployment)

### Application Deployment Pipeline

Workflow: `.github/workflows/app-deploy.yml`

Steps:

1. Checkout
2. PHP + Composer install
3. Node build (Vite/Mix) with cache
4. Package tarball (`app.tar.gz`)
5. OIDC login + deploy via `azure/webapps-deploy`
6. Basic curl smoke test

Adjust target App Service name inside the workflow for dev vs prod (or parameterize with workflow input in future enhancement).

### ML Pipeline

Workflow: `.github/workflows/ml-pipeline.yml`

Scripts in `ml/scripts/`:

- `prepare_data.py` → produce `prepared.csv`
- `train_recommender.py` → train simple placeholder model `model.pkl`
- `register_and_deploy.py` → (stub) simulate registration; extend with `azure.ai.ml` for real deployment.

Environment definition: `environment.yml` (conda-style for local reproducibility if desired).

Future extension suggestions:

- Replace placeholder register script with Azure ML job submission (`mlclient.jobs.create_or_update`)
- Add model endpoint deployment YAML (managed online endpoint) & capture scoring URL + key into Key Vault
- Introduce dataset versioning & metrics logging (MLflow or Azure ML metrics)

### Laravel Integration for ML

Service class: `App\Services\MlRecommendationService` (caches mock recommendations).

When real endpoint is live, replace placeholder block with an HTTP client call:

```php
// Example pseudo-code
$response = Http::withHeaders([
      'Authorization' => 'Bearer '.config('services.ml.key'),
])->post(config('services.ml.endpoint').'/score', [
      'user_id' => $userId,
      'skills' => $skills,
]);
```

Add mapping in `config/services.php`:

```php
'ml' => [
   'endpoint' => env('ML_ENDPOINT_URL'),
   'key' => env('ML_ENDPOINT_KEY'),
],
```

### Azure Blob Storage (Optional)

`config/filesystems.php` contains an `azure` disk placeholder. To enable:

```bash
composer require league/flysystem-azure-blob-storage "^3.0"
```

Set environment variables:

```env
AZURE_STORAGE_NAME=...
AZURE_STORAGE_KEY=... # or use Managed Identity (omit key)
AZURE_STORAGE_CONTAINER=laravel
```

Then access via:

```php
Storage::disk('azure')->put('path/file.txt', 'content');
```

### Operational Recommendations

- Enable staging slot swap practice: deploy to `staging` slot, run health checks, then swap.
- Add alerts (App Insights) for request failure rate & response time.
- Consider adding `Front Door` & WAF when introducing custom domain or global traffic.
- Apply private endpoints once DNS zone & networking design finalized (currently placeholder output).

### Security Notes

- All sensitive connection strings should be Key Vault references (already set in App Service settings).
- Enforce HTTPS (enabled). Consider adding `WEBSITE_MIN_DYNAMIC_WORKER=1` for cold start mitigation.
- Rotate credentials regularly; use Managed Identity for Key Vault & Storage where possible.

### Next Enhancements (Backlog)

- Parameterize app deploy workflow for environment selection.
- Add DB migration & cache warm steps post-deploy with conditional gating.
- Introduce canary validation test suite hitting health endpoint & key routes.
- Implement Azure ML real registration & endpoint deployment.
- Add Terraform alternative or export if multi-cloud required.

---

Cloud & automation section last updated: 2025-10-07.

### Running Deployments

1. Infrastructure (dev example):
   - GitHub → Actions → Infra Deploy → Run workflow → environment=dev
   - Wait for group what-if preview then creation.
2. Add secrets to Key Vault (in Azure Portal or CLI) matching the names in `appservice.bicep` (`APP-KEY`, `DB-HOST`, etc.).
3. Grant Key Vault access:
   - Identity: system-assigned identity of the App Service.
   - Assign role: Key Vault Secrets User (or set access policy if not using RBAC).
4. Application Deploy:
   - GitHub → Actions → App Build & Deploy → Run workflow → environment=dev (or prod).
5. Smoke test automatically pings the web app root; extend by adding a `/simple-health` endpoint if needed.

Rollback Strategy:

- For infra: re-run previous successful workflow with same parameters (idempotent).
- For app: redeploy prior artifact by re-running that workflow run, or trigger workflow using SHA (from run logs checkout commit id).

Key Vault CLI helper (example to set DB host secret):

```bash
az keyvault secret set --vault-name <kv-name> --name DB-HOST --value mysql-pathfinder-dev.mysql.database.azure.com
```

### Local Infrastructure Deployment (Optional)

Use the helper script `infra/deploy.ps1` (requires Azure CLI login):

```powershell
$env:MYSQL_ADMIN_PASSWORD = 'YourSecureP@ssw0rd!'
pwsh ./infra/deploy.ps1 -Environment dev
```

This performs a what-if then a group-scoped deployment using `parameters/dev.json` and injects the secure password at runtime.
