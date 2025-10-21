param location string
param nameSuffix string

var redisName = toLower('redis-${uniqueString(nameSuffix)}')

resource redis 'Microsoft.Cache/redis@2023-08-01' = {
  name: redisName
  location: location
  sku: {
    name: 'Basic'
    family: 'C'
    capacity: 0
  }
  properties: {
    enableNonSslPort: false
    minimumTlsVersion: '1.2'
  }
}

output name string = redis.name
