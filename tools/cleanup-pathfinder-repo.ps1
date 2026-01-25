param(
    [switch]$Apply
)

$ErrorActionPreference = "Stop"

function Exec($cmd, [switch]$Quiet) {
    if ($Quiet) { & cmd /c $cmd > $null 2>&1 } else { & cmd /c $cmd }
    if ($LASTEXITCODE -ne 0) { throw "Command failed: $cmd" }
}

function GitTracked($path) {
    $null = & git ls-files --error-unmatch -- "$path" 2>$null
    return ($LASTEXITCODE -eq 0)
}

function Remove-TrackedOrUntracked([string]$path) {
    if (-not (Test-Path -LiteralPath $path)) { return }
    if (GitTracked $path) {
        Exec "git rm -r -f -- $path"
    }
    else {
        Remove-Item -LiteralPath $path -Recurse -Force -ErrorAction SilentlyContinue
    }
}

# 0) Guard: must be repo root
if (-not (Test-Path ".git")) { throw "Run this from the repo root (folder containing .git)" }

# 1) Working tree assumed clean or already stashed from prior dry-run

# 2) Ensure we are on cleanup branch (created by dry-run)
$branch = (& git branch --show-current).Trim()
if (-not $branch) { throw "Not on a git branch" }

# 3) Build removal plan
$candidatePaths = @(
    # Azure
    "Pathfinder/.github/workflows/azure-deploy.yml",
    "Pathfinder/AZURE_DEPLOYMENT.md",
    "Pathfinder/setup-azure.ps1",
    "Pathfinder/deploy-azure.sh",
    "Pathfinder/infra",

    # Vercel
    "README-VERCEL.md",
    ".env.vercel",
    "generate-vercel-env.php",
    ".github/workflows/vercel-deploy.yml",
    "vercel-deploy.sh",
    "vercel.json",
    "api",

    # Temp / ad-hoc tests
    "temp_modern_radio.txt",
    "temp_design_files",
    "Pathfinder/test_cv_simple.php"
)

$rootGlobs = @("test_cv*.php", "test_cv*.txt")

# If both root and Pathfinder Laravel exist, remove the root duplicate
$removeRootLaravel = (Test-Path "Pathfinder/artisan") -and (Test-Path "artisan")
if ($removeRootLaravel) {
    $candidatePaths += @(
        "artisan",
        "composer.json",
        "composer.lock",
        "package.json",
        "phpunit.xml",
        "Procfile",
        "Dockerfile",
        "public",
        "resources",
        "routes"
    )
}

# Resolve existing items to remove
$toRemove = @()
foreach ($p in $candidatePaths) {
    if (Test-Path -LiteralPath $p) { $toRemove += $p }
}
foreach ($g in $rootGlobs) {
    Get-ChildItem -LiteralPath . -Filter $g -File -ErrorAction SilentlyContinue | ForEach-Object {
        $toRemove += $_.Name
    }
}

if (-not $Apply) {
    Write-Host "Use -Apply to perform removals." -ForegroundColor Yellow
    exit 0
}

foreach ($path in $toRemove) {
    Write-Host "Removing: $path"
    Remove-TrackedOrUntracked $path
}

$keep = @("railway.json", ".editorconfig", ".gitattributes", ".gitignore", "README.md", "pathfinder_database_schema.sql")
foreach ($k in $keep) {
    if (Test-Path -LiteralPath $k) {
        & git restore --staged -- "$k" 2>$null
    }
}

Exec "git status"
param(
    [switch]$Apply
)

$ErrorActionPreference = "Stop"

function Exec($cmd, [switch]$Quiet) {
    if ($Quiet) { & cmd /c $cmd > $null 2>&1 } else { & cmd /c $cmd }
    if ($LASTEXITCODE -ne 0) { throw "Command failed: $cmd" }
}

function GitTracked($path) {
    $null = & git ls-files --error-unmatch -- "$path" 2>$null
    return ($LASTEXITCODE -eq 0)
}

function Remove-TrackedOrUntracked([string]$path) {
    if (-not (Test-Path -LiteralPath $path)) { return }
    if (GitTracked $path) {
        Exec "git rm -r -f -- \"$path\""
    }
    else {
        Remove-Item -LiteralPath $path -Recurse -Force -ErrorAction SilentlyContinue
    }
}

# 0) Guard: must be repo root
if (-not (Test-Path ".git")) { throw "Run this from the repo root (folder containing .git)" }

# 1) Ensure clean working tree
$porcelain = (& git status --porcelain)
if ($porcelain) {
    Write-Host "Working tree is not clean. Stashing changes..." -ForegroundColor Yellow
    Exec "git stash push -u -m \"pre-cleanup WIP\""
}

# 2) Pull latest and create cleanup branch
Exec "git fetch --all --prune"
$originHead = (& git symbolic-ref --short refs/remotes/origin/HEAD 2>$null)
$defaultBranch = if ($originHead) { $originHead.Split('/')[1] } else { "main" }

# Unique branch name
$ts = Get-Date -Format "yyyyMMdd-HHmm"
$branch = "cleanup/railway-slim-$ts"
Exec "git checkout -B $branch origin/$defaultBranch"

# Safety tag
$tag = "pre-cleanup-$ts"
& git tag -a $tag -m "Pre-cleanup snapshot ($ts)" 2>$null

# 3) Build removal plan
$candidatePaths = @(
    # Azure
    "Pathfinder/.github/workflows/azure-deploy.yml",
    "Pathfinder/AZURE_DEPLOYMENT.md",
    "Pathfinder/setup-azure.ps1",
    "Pathfinder/deploy-azure.sh",
    "Pathfinder/infra",

    # Vercel
    "README-VERCEL.md",
    ".env.vercel",
    "generate-vercel-env.php",
    ".github/workflows/vercel-deploy.yml",
    "vercel-deploy.sh",
    "vercel.json",
    "api",

    # Temp / ad-hoc tests
    "temp_modern_radio.txt",
    "temp_design_files",
    "Pathfinder/test_cv_simple.php"
)

$rootGlobs = @("test_cv*.php", "test_cv*.txt")

# If both root and Pathfinder Laravel exist, remove the root duplicate
$removeRootLaravel = (Test-Path "Pathfinder/artisan") -and (Test-Path "artisan")
if ($removeRootLaravel) {
    $candidatePaths += @(
        "artisan",
        "composer.json",
        "composer.lock",
        "package.json",
        "phpunit.xml",
        "Procfile",
        "Dockerfile",
        "public",
        "resources",
        "routes"
    )
}

# 4) Resolve existing items to remove
$toRemove = @()
foreach ($p in $candidatePaths) {
    if (Test-Path -LiteralPath $p) { $toRemove += $p }
}
foreach ($g in $rootGlobs) {
    Get-ChildItem -LiteralPath . -Filter $g -File -ErrorAction SilentlyContinue | ForEach-Object {
        $toRemove += $_.Name
    }
}

# 5) Show plan
if (-not $toRemove -or $toRemove.Count -eq 0) {
    Write-Host "Nothing to remove. Repo already looks clean." -ForegroundColor Green
    Exec "git status"
    exit 0
}

Write-Host "Default branch: origin/$defaultBranch"
Write-Host "Created branch: $branch"
Write-Host "Safety tag: $tag"
Write-Host ""
Write-Host "Planned removals:" -ForegroundColor Yellow
$toRemove | Sort-Object | ForEach-Object { Write-Host "  - $_" }

if (-not $Apply) {
    Write-Host ""; Write-Host "Dry-run only. Re-run with -Apply to perform removals:" -ForegroundColor Cyan
    Write-Host "  powershell -ExecutionPolicy Bypass -File tools/cleanup-pathfinder-repo.ps1 -Apply"
    exit 0
}

# 6) Apply removals
foreach ($path in $toRemove) {
    Write-Host "Removing: $path"
    Remove-TrackedOrUntracked $path
}

# 7) Keep/restore important top-level files if staged accidentally
$keep = @("railway.json", ".editorconfig", ".gitattributes", ".gitignore", "README.md", "pathfinder_database_schema.sql")
foreach ($k in $keep) {
    if (Test-Path -LiteralPath $k) {
        & git restore --staged -- "$k" 2>$null
    }
}

Write-Host ""; Exec "git status"; Write-Host ""
Write-Host "Review the changes above. Then commit and push:" -ForegroundColor Cyan
Write-Host "  git commit -m \"chore: repo cleanup (keep Pathfinder app + Railway)\""
Write-Host "  git push -u origin $branch"
Write-Host ""; Write-Host "Open a Pull Request on GitHub from branch $branch."
