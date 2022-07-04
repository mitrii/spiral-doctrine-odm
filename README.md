## Doctrine ODM connector for Spiral Framework

### Configuration

Create config file in `app/config/doctrine_odm.php`:

Mongo uri and options details [see here](https://www.php.net/manual/en/mongodb-driver-manager.construct.php#refsect1-mongodb-driver-manager.construct-parameters) 

#### Minimal config:
```php
<?php

return [
    'uri' => 'mongodb://mongodb:27017',
];


```


#### All config options:
```php
<?php

return [
        'uri' => 'mongodb://mongodb:27017',
        'uriOptions' => [],
        'defaultDatabase' =>  'db',
        'driverOptions' => [
            'typeMap' => DocumentManager::CLIENT_TYPEMAP,
        ],
        'mappingDriver' => \Doctrine\ODM\MongoDB\Mapping\Driver\AttributeDriver::class,
        
        'proxyDir' => '/runtime/doctrine/proxies',
        'proxyNamespace' => 'DoctrineProxies',
        'autoGenerateProxyClasses' => Configuration::AUTOGENERATE_FILE_NOT_EXISTS,
        
        'hydratorDir' => '/runtime/doctrine/hydrators',
        'hydratorNamespace' => 'DoctrineHydrators',
        'autoGenerateHydratorClasses' => Configuration::AUTOGENERATE_FILE_NOT_EXISTS,
        
        'defaultRepositoryClassName' => DocumentRepository::class,
];


```

### Add Doctrine console commands

```php
// app/config/console.php

//...

    'commands'  => [
        \Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateProxiesCommand::class,
        \Doctrine\ODM\MongoDB\Tools\Console\Command\QueryCommand::class,
        \Doctrine\ODM\MongoDB\Tools\Console\Command\ClearCache\MetadataCommand::class,
        \Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\CreateCommand::class,
        \Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\DropCommand::class,
        \Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\UpdateCommand::class,
        \Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\ShardCommand::class,
    ],
    
// ...    

```
