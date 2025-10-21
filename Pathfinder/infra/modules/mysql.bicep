param location string
param nameSuffix string
param adminUser string
@secure()
param adminPassword string
param version string
param storageGb int
param privateDnsZoneId string

var serverName = toLower('mysql-${uniqueString(nameSuffix)}')

resource mysqlServer 'Microsoft.DBforMySQL/flexibleServers@2023-12-01-preview' = {
  name: serverName
  location: location
  properties: {
    version: version
    administratorLogin: adminUser
    administratorLoginPassword: adminPassword
    storage: {
      storageSizeGB: storageGb
      autoGrow: 'Enabled'
    }
    backup: {
      backupRetentionDays: 7
      geoRedundantBackup: 'Disabled'
    }
    highAvailability: {
      mode: 'Disabled'
    }
    maintenanceWindow: {
      dayOfWeek: 0
      startHour: 0
      startMinute: 0
    }
    network: privateDnsZoneId != ''
      ? {
          privateDnsZoneArmResourceId: privateDnsZoneId
          publicNetworkAccess: 'Disabled'
        }
      : {
          publicNetworkAccess: 'Enabled'
        }
  }
  sku: {
    name: 'B_Standard_B1ms'
    tier: 'Burstable'
  }
}

resource database 'Microsoft.DBforMySQL/flexibleServers/databases@2023-12-01-preview' = {
  name: 'appdb'
  parent: mysqlServer
  properties: {
    charset: 'utf8'
    collation: 'utf8_general_ci'
  }
}

output fqdn string = mysqlServer.properties.fullyQualifiedDomainName
