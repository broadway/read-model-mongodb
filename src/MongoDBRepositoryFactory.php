<?php

declare(strict_types=1);

namespace Broadway\ReadModel\MongoDB;

use Broadway\ReadModel\Repository;
use Broadway\Serializer\Serializer;
use MongoDB\Client;
use MongoDB\Collection;

/**
 * @author Robin van der Vleuten <robin@webstronauts.co>
 */
class MongoDBRepositoryFactory implements RepositoryFactory
{
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function create(Client $client, string $databaseName, string $class): Repository
    {
        $collection = $this->getCollectionFromClass($client, $databaseName, $class);
        return new MongoDBRepository($collection, $this->serializer, $class);
    }

    public function getCollectionFromClass(Client $client, string $databaseName, string $class): Collection
    {
        $classParts = explode('\\', $class);
        $collectionName = end($classParts);

        return $client->selectCollection($databaseName, $collectionName);
    }
}
