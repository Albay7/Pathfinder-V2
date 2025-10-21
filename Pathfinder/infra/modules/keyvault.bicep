param location string
param nameSuffix string

var kvName = toLower('kv-${uniqueString(nameSuffix)}')

resource kv 'Microsoft.KeyVault/vaults@2023-07-01' = {
  name: kvName
  location: location
  properties: {
    tenantId: subscription().tenantId
    sku: {
      family: 'A'
      name: 'standard'
    }
    enablePurgeProtection: true
    enableSoftDelete: true
    softDeleteRetentionInDays: 90
    accessPolicies: [] // Using RBAC instead of access policies
    publicNetworkAccess: 'Enabled'
  }
}

output name string = kv.name
