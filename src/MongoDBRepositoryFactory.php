<?php

declare(strict_types=1);

namespace Broadway\ReadModel\MongoDB;

use Broadway\ReadModel\Repository;
use Broadway\ReadModel\RepositoryFactory;
use Broadway\Serializer\Serializer;
use MongoDB\Collection;

/**
 * @author Robin van der Vleuten <robin@webstronauts.co>
 */
class MongoDBRepositoryFactory implements RepositoryFactory
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(Collection $collection, Serializer $serializer)
    {
        $this->collection = $collection;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $name, string $class): Repository
    {
        return new MongoDBRepository($this->collection, $this->serializer, $class);
    }
}
