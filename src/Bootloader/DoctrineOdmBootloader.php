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
use Mitrii\Spiral\Doctrine\ODM\Config\DoctrineOdmConfig;
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
        $container->bindSingleton(DocumentManager::class, function (
            DirectoriesInterface $dirs,
            Client $client,
            DoctrineOdmConfig $config
        )
        {
            $doctrineConfig = new Configuration();
            $doctrineConfig->setProxyDir($dirs->get('root') . $config->getProxyDir());
            $doctrineConfig->setProxyNamespace($config->getProxyNamespace());
            $doctrineConfig->setAutoGenerateProxyClasses($config->getAutoGenerateProxyClasses());

            $doctrineConfig->setHydratorDir($dirs->get('root') . $config->getHydratorDir());
            $doctrineConfig->setHydratorNamespace($config->getHydratorNamespace());
            $doctrineConfig->setAutoGenerateHydratorClasses($config->getAutoGenerateHydratorClasses());

            $doctrineConfig->setDefaultDB($config->getDefaultDatabase());
            $doctrineConfig->setMetadataDriverImpl(AnnotationDriver::create($dirs->get('root') . $config->getDocumentsDir()));

            $doctrineConfig->setDefaultDocumentRepositoryClassName($config->getDefaultRepositoryClassName());

            return DocumentManager::create($client, $doctrineConfig);
        });

        $finalizer->addFinalizer(function (DocumentManager $documentManager) {
            $documentManager->clear();
        });

        $console->getApplication()->getHelperSet()->set(
            new DocumentManagerHelper($container->get(DocumentManager::class)),
            'documentManager'
        );

    }


    private function initMongoClient(DoctrineOdmConfig $config): Client
    {
        return new Client($config->getUri(), $config->getUriOptions(), $config->getDriverOptions());
    }
}
