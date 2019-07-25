<?php

namespace Broadway\ReadModel\MongoDB;

use Broadway\ReadModel\Repository;
use Broadway\Serializer\SimpleInterfaceSerializer;
use MongoDB\Client;

/**
 * @author Robin van der Vleuten <robin@webstronauts.co>
 */
class MongoDBRepositoryTest extends RepositoryTestCase
{

    protected function createUniqueRepository(string $collectionName, string $readModelClass): Repository
    {
        $collection = (new Client())->selectCollection('broadway_test', $collectionName);

        $collection->drop();

        return new MongoDBRepository($collection, new SimpleInterfaceSerializer(), $readModelClass);
    }
}
