param(
    [Parameter(Mandatory = $true)][ValidateSet('dev', 'prod')][string]$Environment,
    [Parameter(Mandatory = $false)][string]$Location = 'southeastasia'
)

$ErrorActionPreference = 'Stop'

if (-not (Get-Command az -ErrorAction SilentlyContinue)) {
    Write-Error 'Azure CLI (az) not found in PATH.'
}

$paramFile = Join-Path $PSScriptRoot "parameters/$Environment.json"
if (-not (Test-Path $paramFile)) { Write-Error "Parameter file $paramFile not found" }

$rg = "pathfinder-rg-$Environment"
Write-Host "Ensuring resource group $rg in $Location" -ForegroundColor Cyan
az group create -n $rg -l $Location | Out-Null

if (-not $env:MYSQL_ADMIN_PASSWORD) {
    Write-Error 'Set environment variable MYSQL_ADMIN_PASSWORD before running (secure string).'
}

Write-Host 'Validating template (what-if)...' -ForegroundColor Yellow
az deployment group what-if `
    --resource-group $rg `
    --template-file (Join-Path $PSScriptRoot 'main.bicep') `
    --parameters @$paramFile mysqlAdminPassword=$env:MYSQL_ADMIN_PASSWORD

Write-Host 'Deploying...' -ForegroundColor Yellow
az deployment group create `
    --resource-group $rg `
    --template-file (Join-Path $PSScriptRoot 'main.bicep') `
    --parameters @$paramFile mysqlAdminPassword=$env:MYSQL_ADMIN_PASSWORD `
    --name pathfinder-$Environment-$(Get-Date -Format yyyyMMddHHmmss)

Write-Host 'Deployment complete.' -ForegroundColor Green
