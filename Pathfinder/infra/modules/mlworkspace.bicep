param location string
param nameSuffix string

var workspaceName = 'ml-${nameSuffix}'

resource aml 'Microsoft.MachineLearningServices/workspaces@2024-04-01' = {
  name: workspaceName
  location: location
  properties: {
    friendlyName: 'Pathfinder ML'
    description: 'ML workspace for recommendation models'
    publicNetworkAccess: 'Enabled'
  }
  sku: {
    name: 'Basic'
    tier: 'Basic'
  }
}

output workspaceName string = aml.name
