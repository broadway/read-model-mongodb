<?php

/*
 * This file is part of the broadway/read-model-mongodb package.
 *
 * (c) 2020 Broadway project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
    protected function createRepository(): Repository
    {
        $collection = (new Client())
            ->selectCollection('broadway', 'test');

        $collection->drop();

        return new MongoDBRepository($collection, new SimpleInterfaceSerializer(), RepositoryTestReadModel::class);
    }
}
