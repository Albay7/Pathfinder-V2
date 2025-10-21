param location string
param nameSuffix string
param skuName string
param skuTier string

resource plan 'Microsoft.Web/serverfarms@2023-01-01' = {
  name: 'asp-${nameSuffix}'
  location: location
  sku: {
    name: skuName
    tier: skuTier
    capacity: 1
  }
  properties: {
    reserved: true // Linux
  }
}

output id string = plan.id
