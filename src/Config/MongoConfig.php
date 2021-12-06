<?php
/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace Mitrii\Spiral\Doctrine\ODM\Config;

use Spiral\Core\InjectableConfig;

class MongoConfig extends InjectableConfig
{
    public const CONFIG = 'mongo';

    /**
     * @internal For internal usage. Will be hydrated in the constructor.
     */
    protected $config = [
        'host' => 'mongodb://mongodb:27017',
        'database' =>  'db',
        'options' => [],
        'collections' => [
        ],
    ];

    public function getHost(): string
    {
        return $this->offsetGet('host');
    }

    public function getUriOptions(): array
    {
        return $this->offsetGet('options');
    }

    public function getDatabase(): string
    {
        return $this->offsetGet('database');
    }
}
