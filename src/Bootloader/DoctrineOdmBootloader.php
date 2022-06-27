<?php

declare(strict_types=1);

namespace Mitrii\Spiral\Doctrine\ODM\Bootloader;

use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AttributeDriver;
use Doctrine\ODM\MongoDB\Tools\Console\Helper\DocumentManagerHelper;
use Mitrii\Spiral\Doctrine\ODM\Config\DoctrineOdmConfig;
use MongoDB\Client;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Boot\FinalizerInterface;
use Spiral\Console\Console;
use Spiral\Core\Container;
use Symfony\Component\Console\Application;
use Throwable;

class DoctrineOdmBootloader extends Bootloader
{
    protected const BINDINGS = [
        Client::class => [self::class, 'initMongoClient'],
    ];

    /**
     * @throws Throwable
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function boot(Container $container, Console $console): void
    {
        $container->bindSingleton(DocumentManager::class, function (
            DirectoriesInterface $dirs,
            Client $client,
            DoctrineOdmConfig $config,
            FinalizerInterface $finalizer,
            Container $container
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

            $mappingDriver = $container->make($config->getMappingDriver());

            $doctrineConfig->setMetadataDriverImpl(
                $mappingDriver::create($dirs->get('root') . $config->getDocumentsDir()));

            $doctrineConfig->setDefaultDocumentRepositoryClassName($config->getDefaultRepositoryClassName());

            $finalizer->addFinalizer(function () use ($container) {
                $documentManager = $container->get(DocumentManager::class);
                $documentManager->clear();
            });

            return DocumentManager::create($client, $doctrineConfig);
        });

        $application = $container->get(Application::class);
        if ($application instanceof Application) {
            $console->getApplication()->getHelperSet()->set(
                new DocumentManagerHelper($container->get(DocumentManager::class)),
                'documentManager'
            );
        }
    }

    private function initMongoClient(DoctrineOdmConfig $config): Client
    {
        return new Client($config->getUri(), $config->getUriOptions(), $config->getDriverOptions());
    }
}
