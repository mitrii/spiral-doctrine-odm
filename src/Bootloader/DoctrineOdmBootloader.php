<?php
/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace Mitrii\Spiral\Doctrine\ODM\Bootloader;

use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\Tools\Console\Helper\DocumentManagerHelper;
use Mitrii\Spiral\Doctrine\ODM\Config\MongoConfig;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Boot\FinalizerInterface;
use Spiral\Console\Console;
use Spiral\Core\Container;
use MongoDB\Client;

class DoctrineOdmBootloader extends Bootloader
{
    protected const BINDINGS = [
        Client::class => [self::class, 'initMongoClient'],
    ];

    protected const SINGLETONS = [];

    protected const DEPENDENCIES = [];

    public function boot(Container $container, Console $console, FinalizerInterface $finalizer): void
    {
        $container->bindSingleton(DocumentManager::class, function (DirectoriesInterface $dirs, Client $client, MongoConfig $mongoConfig)
        {
            $config = new Configuration();
            $config->setProxyDir($dirs->get('root') . '/app/src/Proxy');
            $config->setProxyNamespace('Proxy');

            $config->setHydratorDir($dirs->get('root') . '/app/src/Hydrator');
            $config->setHydratorNamespace('Hydrator');

            $config->setDefaultDB($mongoConfig->getDatabase());
            $config->setMetadataDriverImpl(AnnotationDriver::create($dirs->get('root') . '/app/src/Document'));

            return DocumentManager::create($client, $config);
        });

        $console->getApplication()->getHelperSet()->set(
            new DocumentManagerHelper($container->get(DocumentManager::class)),
            'documentManager'
        );

        $finalizer->addFinalizer(function (DocumentManager $documentManager)
        {
            $documentManager->clear();
        });

    }


    private function initMongoClient(MongoConfig $mongoConfig): Client
    {
        return new Client(
            $mongoConfig->getHost(),
            $mongoConfig->getUriOptions(),
            [
                'typeMap' => DocumentManager::CLIENT_TYPEMAP
            ]
        );
    }
}
