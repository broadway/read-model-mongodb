<?php

namespace Broadway\ReadModel\MongoDB;

use Broadway\ReadModel\Repository;
use Broadway\ReadModel\Testing\RepositoryTestCase;
use Broadway\ReadModel\Testing\RepositoryTestReadModel;
use Broadway\Serializer\SimpleInterfaceSerializer;
use MongoDB\Client;

/**
 * @author Robin van der Vleuten <robin@webstronauts.co>
 */
class MongoDBRepositoryTest extends RepositoryTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createRepository(): Repository
    {
        $collection = (new Client())
            ->selectCollection('broadway', 'test');

        $collection->drop();

        return new MongoDBRepository($collection, new SimpleInterfaceSerializer(), RepositoryTestReadModel::class);
    }
}
