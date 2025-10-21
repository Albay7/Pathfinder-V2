param location string
param nameSuffix string

var storageName = toLower(replace('st${uniqueString(nameSuffix)}', '-', ''))

resource storage 'Microsoft.Storage/storageAccounts@2023-01-01' = {
  name: storageName
  location: location
  sku: {
    name: 'Standard_LRS'
  }
  kind: 'StorageV2'
  properties: {
    accessTier: 'Hot'
    allowBlobPublicAccess: false
    minimumTlsVersion: 'TLS1_2'
  }
}

resource blobContainer 'Microsoft.Storage/storageAccounts/blobServices/containers@2023-01-01' = {
  name: '${storage.name}/default/laravel'
  properties: {
    publicAccess: 'None'
  }
}

output storageName string = storage.name
