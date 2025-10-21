param location string
param nameSuffix string
param appServicePlanId string
param insightsConnectionString string
param keyVaultName string
param storageAccountName string
param enableSlot bool

resource app 'Microsoft.Web/sites@2023-12-01' = {
  name: 'app-${nameSuffix}'
  location: location
  kind: 'app,linux'
  identity: {
    type: 'SystemAssigned'
  }
  properties: {
    serverFarmId: appServicePlanId
    siteConfig: {
      linuxFxVersion: 'PHP|8.2'
      // Key Vault secret references rely on system-assigned identity
      appSettings: [
        {
          name: 'APP_ENV'
          value: 'production'
        }
        {
          name: 'APP_STORAGE_ACCOUNT'
          value: storageAccountName
        }
        {
          name: 'APPLICATIONINSIGHTS_CONNECTION_STRING'
          value: insightsConnectionString
        }
        {
          name: 'WEBSITE_RUN_FROM_PACKAGE'
          value: '0'
        }
        {
          name: 'WEBSITES_ENABLE_APP_SERVICE_STORAGE'
          value: 'false'
        }
        {
          name: 'APP_KEY'
          value: '@Microsoft.KeyVault(VaultName=${keyVaultName};SecretName=APP-KEY)'
        }
        {
          name: 'DB_HOST'
          value: '@Microsoft.KeyVault(VaultName=${keyVaultName};SecretName=DB-HOST)'
        }
        {
          name: 'DB_DATABASE'
          value: '@Microsoft.KeyVault(VaultName=${keyVaultName};SecretName=DB-NAME)'
        }
        {
          name: 'DB_USERNAME'
          value: '@Microsoft.KeyVault(VaultName=${keyVaultName};SecretName=DB-USER)'
        }
        {
          name: 'DB_PASSWORD'
          value: '@Microsoft.KeyVault(VaultName=${keyVaultName};SecretName=DB-PASSWORD)'
        }
        {
          name: 'REDIS_HOST'
          value: '@Microsoft.KeyVault(VaultName=${keyVaultName};SecretName=REDIS-HOST)'
        }
        {
          name: 'REDIS_PASSWORD'
          value: '@Microsoft.KeyVault(VaultName=${keyVaultName};SecretName=REDIS-PASSWORD)'
        }
        {
          name: 'ML_ENDPOINT_URL'
          value: '@Microsoft.KeyVault(VaultName=${keyVaultName};SecretName=ML-ENDPOINT-URL)'
        }
        {
          name: 'ML_ENDPOINT_KEY'
          value: '@Microsoft.KeyVault(VaultName=${keyVaultName};SecretName=ML-ENDPOINT-KEY)'
        }
      ]
      http20Enabled: true
      alwaysOn: true
    }
    httpsOnly: true
  }
}

resource slot 'Microsoft.Web/sites/slots@2023-12-01' = if (enableSlot) {
  name: 'staging'
  parent: app
  location: location
  identity: {
    type: 'SystemAssigned'
  }
  properties: {
    serverFarmId: appServicePlanId
    siteConfig: {
      linuxFxVersion: 'PHP|8.2'
      appSettings: [
        {
          name: 'APP_ENV'
          value: 'staging'
        }
        {
          name: 'APPLICATIONINSIGHTS_CONNECTION_STRING'
          value: insightsConnectionString
        }
      ]
      http20Enabled: true
    }
    httpsOnly: true
  }
}

output defaultHostName string = app.properties.defaultHostName
