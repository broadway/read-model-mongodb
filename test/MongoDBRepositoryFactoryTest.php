<?php

namespace Broadway\ReadModel\MongoDB;

use Broadway\Serializer\Serializer;
use MongoDB\Collection;
use PHPUnit_Framework_TestCase;

/**
 * @author Robin van der Vleuten <robin@webstronauts.co>
 */
class MongoDBRepositoryFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_creates_a_mongodb_repository()
    {
        $collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $serializer = $this->getMock(Serializer::class);

        $repository = new MongoDBRepository($collection, $serializer, '[CLASS]');
        $factory = new MongoDBRepositoryFactory($collection, $serializer);

        $this->assertEquals($repository, $factory->create('test', '[CLASS]'));
    }
}
