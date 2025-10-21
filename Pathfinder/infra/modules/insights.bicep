param location string
param nameSuffix string

var appInsightsName = 'ai-${nameSuffix}'
var logAnalyticsName = 'log-${nameSuffix}'

resource log 'Microsoft.OperationalInsights/workspaces@2022-10-01' = {
  name: logAnalyticsName
  location: location
  properties: {
    retentionInDays: 30
    sku: {
      name: 'PerGB2018'
    }
  }
}

resource insights 'Microsoft.Insights/components@2020-02-02' = {
  name: appInsightsName
  location: location
  kind: 'web'
  properties: {
    Application_Type: 'web'
    WorkspaceResourceId: log.id
    Flow_Type: 'Bluefield'
  }
}

output appInsightsName string = insights.name
output appInsightsConnectionString string = insights.properties.ConnectionString
