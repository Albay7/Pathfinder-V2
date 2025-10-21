@description('Deployment environment name (dev|prod)')
param environment string = 'dev'
@description('Azure region')
param location string = 'southeastasia'
@description('Global name prefix')
param prefix string = 'pathfinder'

// Suffix resources with environment for isolation
var nameSuffix = '${prefix}-${environment}'

@description('Admin username for MySQL (Do NOT store password here)')
param mysqlAdminUser string = 'pfadmin'
@description('Admin password for MySQL (secure)')
@secure()
param mysqlAdminPassword string
@description('MySQL version')
param mysqlVersion string = '8.0.21'
@description('MySQL storage in GB')
param mysqlStorageGb int = 32

@description('App Service Plan SKU (B1 for low cost start)')
param planSkuName string = 'B1'
@description('App Service Plan tier')
param planSkuTier string = 'Basic'

@description('Enable staging slot')
param enableSlot bool = true
@description('Enable Redis cache')
param enableRedis bool = true
@description('Enable private endpoints')
param enablePrivateEndpoints bool = true

// Networking (always provision base VNet; feature flags still govern private endpoint usage later)
module vnet 'modules/vnet.bicep' = {
  name: 'vnet-${nameSuffix}'
  params: {
    location: location
    name: 'vnet-${nameSuffix}'
  }
}

module insights 'modules/insights.bicep' = {
  name: 'insights-${nameSuffix}'
  params: {
    location: location
    nameSuffix: nameSuffix
  }
}

module storage 'modules/storage.bicep' = {
  name: 'st-${nameSuffix}'
  params: {
    location: location
    nameSuffix: nameSuffix
  }
}

module keyVault 'modules/keyvault.bicep' = {
  name: 'kv-${nameSuffix}'
  params: {
    location: location
    nameSuffix: nameSuffix
  }
}

module mysql 'modules/mysql.bicep' = {
  name: 'mysql-${nameSuffix}'
  params: {
    location: location
    nameSuffix: nameSuffix
    adminUser: mysqlAdminUser
    adminPassword: mysqlAdminPassword
    version: mysqlVersion
    storageGb: mysqlStorageGb
    privateDnsZoneId: enablePrivateEndpoints ? vnet.outputs.privateDnsZoneId : ''
  }
}

module redis 'modules/redis.bicep' = {
  name: 'redis-${nameSuffix}'
  params: {
    location: location
    nameSuffix: nameSuffix
  }
}

module appServicePlan 'modules/appserviceplan.bicep' = {
  name: 'plan-${nameSuffix}'
  params: {
    location: location
    nameSuffix: nameSuffix
    skuName: planSkuName
    skuTier: planSkuTier
  }
}

module appService 'modules/appservice.bicep' = {
  name: 'app-${nameSuffix}'
  params: {
    location: location
    nameSuffix: nameSuffix
    appServicePlanId: appServicePlan.outputs.id
    insightsConnectionString: insights.outputs.appInsightsConnectionString
    keyVaultName: keyVault.outputs.name
    storageAccountName: storage.outputs.storageName
    enableSlot: enableSlot
  }
}

module ml 'modules/mlworkspace.bicep' = {
  name: 'ml-${nameSuffix}'
  params: {
    location: location
    nameSuffix: nameSuffix
  }
}

output resourceSummary object = {
  appService: appService.outputs.defaultHostName
  storage: storage.outputs.storageName
  keyVault: keyVault.outputs.name
  mysqlFqdn: mysql.outputs.fqdn
  redisName: enableRedis ? redis.outputs.name : ''
  appInsights: insights.outputs.appInsightsName
  mlWorkspace: ml.outputs.workspaceName
}
