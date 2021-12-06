## Doctrine ODM connector for Spiral Framework

### Add Doctrine console commands

```php
// config/console.php

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
