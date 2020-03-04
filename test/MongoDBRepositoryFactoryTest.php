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

use Broadway\Serializer\Serializer;
use MongoDB\Collection;
use PHPUnit\Framework\TestCase;

/**
 * @author Robin van der Vleuten <robin@webstronauts.co>
 */
class MongoDBRepositoryFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_a_mongodb_repository()
    {
        $collection = $this->createMock(Collection::class);

        $serializer = $this->createMock(Serializer::class);

        $repository = new MongoDBRepository($collection, $serializer, '[CLASS]');
        $factory = new MongoDBRepositoryFactory($collection, $serializer);

        $this->assertEquals($repository, $factory->create('test', '[CLASS]'));
    }
}
