# Azure Deployment Guide for Pathfinder

This guide provides step-by-step instructions for deploying the Pathfinder application to Azure using GitHub Actions.

## Prerequisites

- Azure account with an active subscription
- GitHub account with your Pathfinder repository
- Access to Azure Cloud Shell or Azure CLI installed locally

## Option 1: Deploy Using Azure Cloud Shell (Recommended)

The easiest way to deploy is using the provided Azure CLI script directly in Azure Cloud Shell:

1. **Upload the deployment script to Azure Cloud Shell**:
   - Open Azure Cloud Shell (bash)
   - Upload `deploy-azure.sh` using the upload button
   - Make it executable: `chmod +x deploy-azure.sh`

2. **Edit the configuration variables**:
   - Open the script: `nano deploy-azure.sh`
   - Update the variables at the top (resource group, location, app name, etc.)
   - Save the file (Ctrl+O, then Enter, then Ctrl+X)

3. **Run the deployment script**:
   ```bash
   ./deploy-azure.sh
   ```

4. **Set up GitHub secrets**:
   - The script will output the necessary GitHub secrets
   - You can set them manually or use the `setup-github-secrets.sh` script

## Option 2: Deploy Using PowerShell

If you prefer PowerShell, you can use the provided PowerShell script:

```powershell
# Navigate to your project directory
cd path\to\Pathfinder

# Run the setup script with your parameters
.\setup-azure.ps1 -ResourceGroupName "pathfinder-rg" -Location "eastus" -AppName "pathfinder-app" -DbAdminUsername "pathfinder_admin" -DbAdminPassword "YourStrongPassword123!"
```

## Setting Up GitHub Secrets

You can set up GitHub secrets manually or use the provided script:

### Manual Setup

1. Go to your GitHub repository → Settings → Secrets and variables → Actions
2. Add the following secrets:
   - `AZURE_CREDENTIALS`: The JSON output from the service principal creation
   - `AZURE_WEBAPP_NAME`: Your Azure Web App name (e.g., "pathfinder-app")

### Automated Setup (Using GitHub CLI)

1. Upload `setup-github-secrets.sh` to your environment
2. Edit the variables at the top of the script
3. Make it executable: `chmod +x setup-github-secrets.sh`
4. Run the script: `./setup-github-secrets.sh`

## Deployment Process

The GitHub Actions workflow will automatically deploy your application when you push to the main branch.

Alternatively, you can manually trigger the workflow:

1. Go to your GitHub repository → Actions
2. Select the "Deploy to Azure" workflow
3. Click "Run workflow"
4. Select the environment (dev or prod)
5. Click "Run workflow"

## Troubleshooting

If you encounter issues during deployment:

1. Check the GitHub Actions logs for detailed error messages
2. Verify that all GitHub secrets are correctly set
3. Ensure your Azure resources are properly configured
4. Check the Azure Web App logs in the Azure Portal