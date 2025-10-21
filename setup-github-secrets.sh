#!/bin/bash

# GitHub Secrets Setup Script for Pathfinder
# This script sets up the required GitHub secrets for Azure deployment

# Configuration variables - CHANGE THESE
GITHUB_REPO="your-github-username/pathfinder" # Change this!
APP_NAME="pathfinder-app"                     # Must match the name in deploy-azure.sh
AZURE_CREDENTIALS=""                          # Paste the JSON output from the service principal creation here

# Check if GitHub CLI is installed
if ! command -v gh &> /dev/null; then
    echo "GitHub CLI is not installed. Please install it first:"
    echo "https://cli.github.com/manual/installation"
    exit 1
fi

# Check if user is logged in to GitHub
echo "Checking GitHub authentication..."
if ! gh auth status &> /dev/null; then
    echo "You are not logged in to GitHub. Please login:"
    gh auth login
fi

# Set GitHub secrets
echo "Setting up GitHub secrets for repository: $GITHUB_REPO"

if [ -z "$AZURE_CREDENTIALS" ]; then
    echo "AZURE_CREDENTIALS is empty. Please run the deploy-azure.sh script first and copy the service principal JSON."
    exit 1
fi

# Set AZURE_CREDENTIALS secret
echo "Setting AZURE_CREDENTIALS secret..."
gh secret set AZURE_CREDENTIALS -b"$AZURE_CREDENTIALS" -R $GITHUB_REPO

# Set AZURE_WEBAPP_NAME secret
echo "Setting AZURE_WEBAPP_NAME secret..."
gh secret set AZURE_WEBAPP_NAME -b"$APP_NAME" -R $GITHUB_REPO

echo "GitHub secrets setup complete!"
echo "Your GitHub Actions workflow is now ready to deploy to Azure."