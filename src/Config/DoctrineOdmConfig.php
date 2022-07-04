<?php
/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace Mitrii\Spiral\Doctrine\ODM\Config;

use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\Mapping\Driver\AttributeDriver;
use Spiral\Core\InjectableConfig;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class DoctrineOdmConfig extends InjectableConfig
{
    public const CONFIG = 'doctrine_odm';

    /**
     *
     * @internal For internal usage. Will be hydrated in the constructor.
     */
    protected $config = [
        'uri' => 'mongodb://mongodb:27017',
        'uriOptions' => [],
        'defaultDatabase' =>  'db',
        'driverOptions' => [
            'typeMap' => DocumentManager::CLIENT_TYPEMAP,
        ],
        'mappingDriver' => AttributeDriver::class,
        'proxyDir' => '/runtime/doctrine/proxies',
        'proxyNamespace' => 'DoctrineProxies',
        'autoGenerateProxyClasses' => Configuration::AUTOGENERATE_FILE_NOT_EXISTS,
        'hydratorDir' => '/runtime/doctrine/hydrators',
        'hydratorNamespace' => 'DoctrineHydrators',
        'autoGenerateHydratorClasses' => Configuration::AUTOGENERATE_FILE_NOT_EXISTS,
        'defaultRepositoryClassName' => DocumentRepository::class,
    ];

    public function getUri(): string
    {
        return $this->offsetGet('uri');
    }

    public function getUriOptions(): array
    {
        return $this->offsetExists('uriOptions') ? $this->offsetGet('uriOptions') : [];
    }

    public function getDefaultDatabase(): string
    {
        return $this->offsetExists('defaultDatabase') ? $this->offsetGet('defaultDatabase') : 'db';
    }

    public function getMappingDriver(): string
    {
        if (! $this->offsetExists('mappingDriver')) {
            return AnnotationDriver::class;
        }

        return $this->offsetGet('mappingDriver');
    }

    public function getDocumentsDir(): string
    {
        return $this->offsetExists('documentsDir') ? $this->offsetGet('proxyDir') : '/app/src/Document';
    }

    public function getProxyDir(): string
    {
        return $this->offsetExists('proxyDir') ? $this->offsetGet('proxyDir') : '/runtime/doctrine/proxies';
    }

    public function getProxyNamespace(): string
    {
        return $this->offsetExists('proxyNamespace') ? $this->offsetGet('proxyNamespace') : 'DoctrineProxies';
    }

    public function getAutoGenerateProxyClasses(): int
    {
        return $this->offsetExists('autoGenerateProxyClasses') ? $this->offsetGet('autoGenerateProxyClasses') : Configuration::AUTOGENERATE_FILE_NOT_EXISTS;
    }

    public function getHydratorDir(): string
    {
        return $this->offsetExists('hydratorDir') ? $this->offsetGet('hydratorDir') : '/runtime/doctrine/hydrators';
    }

    public function getHydratorNamespace(): string
    {
        return $this->offsetExists('hydratorNamespace') ? $this->offsetGet('hydratorNamespace') : 'DoctrineHydrators';
    }

    public function getAutoGenerateHydratorClasses(): int
    {
        return $this->offsetExists('autoGenerateHydratorClasses') ? $this->offsetGet('autoGenerateHydratorClasses') : Configuration::AUTOGENERATE_FILE_NOT_EXISTS;
    }

    public function getDefaultRepositoryClassName(): string
    {
        return $this->offsetExists('defaultRepositoryClassName') ? $this->offsetGet('defaultRepositoryClassName') : DocumentRepository::class;
    }

    public function getDriverOptions()
    {
        return $this->offsetExists('driverOptions') ? $this->offsetGet('driverOptions') : [
            'typeMap' => DocumentManager::CLIENT_TYPEMAP
        ];
    }
}
