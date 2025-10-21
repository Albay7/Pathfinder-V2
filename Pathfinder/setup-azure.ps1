param(
    [Parameter(Mandatory=$true)]
    [string]$ResourceGroupName,
    
    [Parameter(Mandatory=$true)]
    [string]$Location,
    
    [Parameter(Mandatory=$true)]
    [string]$AppName,
    
    [Parameter(Mandatory=$true)]
    [string]$DbAdminUsername,
    
    [Parameter(Mandatory=$true)]
    [string]$DbAdminPassword
)

# Login to Azure (uncomment if not already logged in)
# az login

# Create Resource Group
Write-Host "Creating Resource Group: $ResourceGroupName in $Location..."
az group create --name $ResourceGroupName --location $Location

# Create App Service Plan
$AppServicePlanName = "$AppName-plan"
Write-Host "Creating App Service Plan: $AppServicePlanName..."
az appservice plan create --name $AppServicePlanName --resource-group $ResourceGroupName --sku B1 --is-linux

# Create Web App
Write-Host "Creating Web App: $AppName..."
az webapp create --name $AppName --resource-group $ResourceGroupName --plan $AppServicePlanName --runtime "PHP:8.2"

# Configure Web App Settings
Write-Host "Configuring Web App Settings..."
az webapp config set --name $AppName --resource-group $ResourceGroupName --startup-file "/home/site/wwwroot/public/index.php"
az webapp config appsettings set --name $AppName --resource-group $ResourceGroupName --settings SCM_DO_BUILD_DURING_DEPLOYMENT=true

# Create MySQL Server
$MysqlServerName = "$AppName-mysql"
Write-Host "Creating MySQL Server: $MysqlServerName..."
az mysql flexible-server create --name $MysqlServerName --resource-group $ResourceGroupName --location $Location --admin-user $DbAdminUsername --admin-password $DbAdminPassword --sku-name Standard_B1ms --storage-size 20 --version 8.0

# Create Database
$DatabaseName = "pathfinder"
Write-Host "Creating Database: $DatabaseName..."
az mysql flexible-server db create --resource-group $ResourceGroupName --server-name $MysqlServerName --database-name $DatabaseName

# Allow Web App to access MySQL
Write-Host "Configuring MySQL Firewall Rules..."
az mysql flexible-server firewall-rule create --name AllowAzureServices --resource-group $ResourceGroupName --server-name $MysqlServerName --start-ip-address 0.0.0.0 --end-ip-address 0.0.0.0

# Configure Web App with MySQL Connection
$MysqlHost = "$MysqlServerName.mysql.database.azure.com"
Write-Host "Configuring Web App with MySQL Connection..."
az webapp config appsettings set --name $AppName --resource-group $ResourceGroupName --settings DB_CONNECTION=mysql DB_HOST=$MysqlHost DB_PORT=3306 DB_DATABASE=$DatabaseName DB_USERNAME=$DbAdminUsername DB_PASSWORD=$DbAdminPassword

# Output important information
Write-Host "`nAzure Resources Setup Complete!"
Write-Host "Resource Group: $ResourceGroupName"
Write-Host "Web App Name: $AppName"
Write-Host "Web App URL: https://$AppName.azurewebsites.net"
Write-Host "MySQL Server: $MysqlServerName"
Write-Host "MySQL Database: $DatabaseName"
Write-Host "`nImportant: Add the following secrets to your GitHub repository:"
Write-Host "AZURE_CREDENTIALS: (Create using az ad sp create-for-rbac command)"
Write-Host "AZURE_WEBAPP_NAME: $AppName"