#!/bin/bash

# Azure CLI Deployment Script for Pathfinder
# This script automates the deployment of Pathfinder to Azure

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to display messages
print_message() {
  echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
  echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Configuration variables - CHANGE THESE
RESOURCE_GROUP="pathfinder-rg"
LOCATION="westeurope"  # Changed to westeurope which has better support
APP_NAME="pathfinder-app"
DB_SERVER_NAME="pathfinder-mysql"
DB_NAME="pathfinder_DB"
DB_USERNAME="Pathfinder_User"
DB_PASSWORD="YourStrongPassword123!" # Change this!
GITHUB_REPO="Albay7/Pathfinder-V2" # Change this!

# Confirm settings
echo "Azure Deployment Configuration:"
echo "------------------------------"
echo "Resource Group: $RESOURCE_GROUP"
echo "Location: $LOCATION"
echo "App Name: $APP_NAME"
echo "MySQL Server: $DB_SERVER_NAME"
echo "Database Name: $DB_NAME"
echo "Database Username: $DB_USERNAME"
echo "GitHub Repository: $GITHUB_REPO"
echo "------------------------------"
echo ""

read -p "Continue with these settings? (y/n): " confirm
if [[ $confirm != "y" && $confirm != "Y" ]]; then
  echo "Deployment cancelled."
  exit 1
fi

# Create Resource Group
print_message "Creating Resource Group: $RESOURCE_GROUP"
az group create --name $RESOURCE_GROUP --location $LOCATION

# List available locations for App Service
print_message "Checking available locations for App Service"
echo "Available locations:"
az appservice list-locations --sku B1 --linux-workers-enabled

# Create App Service Plan with user confirmation
print_message "Creating App Service Plan"
read -p "Enter location from the list above (e.g., westeurope): " APP_LOCATION
if [ -z "$APP_LOCATION" ]; then
  APP_LOCATION=$LOCATION
  echo "Using default location: $APP_LOCATION"
fi

az appservice plan create --name "${APP_NAME}-plan" --resource-group $RESOURCE_GROUP --sku B1 --is-linux --location $APP_LOCATION

# Create Web App
print_message "Creating Web App: $APP_NAME"
az webapp create --name $APP_NAME --resource-group $RESOURCE_GROUP --plan "${APP_NAME}-plan" --runtime "PHP|8.2"

# Configure Web App Settings
print_message "Configuring Web App Settings"
az webapp config set --name $APP_NAME --resource-group $RESOURCE_GROUP --startup-file "/home/site/wwwroot/public/index.php"
az webapp config appsettings set --name $APP_NAME --resource-group $RESOURCE_GROUP --settings SCM_DO_BUILD_DURING_DEPLOYMENT=true

# Create MySQL Server
print_message "Creating MySQL Server: $DB_SERVER_NAME"
print_message "Checking available locations for MySQL Flexible Server"
echo "Available locations:"
az mysql flexible-server list-skus --output table

read -p "Enter location for MySQL server (e.g., westeurope): " MYSQL_LOCATION
if [ -z "$MYSQL_LOCATION" ]; then
  MYSQL_LOCATION=$APP_LOCATION
  echo "Using App Service location: $MYSQL_LOCATION"
fi

# Create MySQL Server with interactive confirmation
az mysql flexible-server create \
  --name $DB_SERVER_NAME \
  --resource-group $RESOURCE_GROUP \
  --location $MYSQL_LOCATION \
  --admin-user $DB_USERNAME \
  --admin-password $DB_PASSWORD \
  --sku-name Standard_B1s \
  --tier Burstable \
  --storage-size 20 \
  --version 8.0

# Create Database
print_message "Creating Database: $DB_NAME"
az mysql flexible-server db create \
  --resource-group $RESOURCE_GROUP \
  --server-name $DB_SERVER_NAME \
  --database-name $DB_NAME

# Allow Web App to access MySQL
print_message "Configuring MySQL Firewall Rules"
az mysql flexible-server firewall-rule create \
  --name AllowAzureServices \
  --resource-group $RESOURCE_GROUP \
  --server-name $DB_SERVER_NAME \
  --start-ip-address 0.0.0.0 \
  --end-ip-address 0.0.0.0

# Configure Web App with MySQL Connection
print_message "Configuring Web App with MySQL Connection"
MYSQL_HOST="${DB_SERVER_NAME}.mysql.database.azure.com"
az webapp config appsettings set --name $APP_NAME --resource-group $RESOURCE_GROUP --settings \
  DB_CONNECTION=mysql \
  DB_HOST=$MYSQL_HOST \
  DB_PORT=3306 \
  DB_DATABASE=$DB_NAME \
  DB_USERNAME=$DB_USERNAME \
  DB_PASSWORD=$DB_PASSWORD \
  APP_ENV=production \
  APP_DEBUG=false \
  APP_URL=https://${APP_NAME}.azurewebsites.net

# Create service principal for GitHub Actions
print_message "Creating service principal for GitHub Actions"
SP_JSON=$(az ad sp create-for-rbac --name "${APP_NAME}-github-actions" --role contributor --scopes /subscriptions/$(az account show --query id -o tsv)/resourceGroups/$RESOURCE_GROUP --sdk-auth)

# Output important information
echo ""
print_message "Azure Resources Setup Complete!"
echo "Resource Group: $RESOURCE_GROUP"
echo "Web App Name: $APP_NAME"
echo "Web App URL: https://$APP_NAME.azurewebsites.net"
echo "MySQL Server: $DB_SERVER_NAME"
echo "MySQL Database: $DB_NAME"
echo ""
print_message "GitHub Actions Setup"
echo "Add the following secrets to your GitHub repository:"
echo "AZURE_CREDENTIALS: $SP_JSON"
echo "AZURE_WEBAPP_NAME: $APP_NAME"
echo "AZURE_RESOURCE_GROUP: $RESOURCE_GROUP"
echo ""
print_message "To set up GitHub secrets automatically, run:"
echo "gh auth login"
echo "gh secret set AZURE_CREDENTIALS -b'$SP_JSON' -R $GITHUB_REPO"
echo "gh secret set AZURE_WEBAPP_NAME -b'$APP_NAME' -R $GITHUB_REPO"
echo "gh secret set AZURE_RESOURCE_GROUP -b'$RESOURCE_GROUP' -R $GITHUB_REPO"
echo ""
print_message "Deployment Complete! Your application will be deployed automatically when you push to the main branch."