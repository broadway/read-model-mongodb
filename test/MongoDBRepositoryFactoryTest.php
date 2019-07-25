<?php

namespace Broadway\ReadModel\MongoDB;

use Broadway\Serializer\Serializer;
use MongoDB\Client;
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

        $serializer = $this->getMockBuilder(Serializer::class)
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client
            ->method('selectCollection')
            ->willReturn($collection);

        $repository = new MongoDBRepository($collection, $serializer, '[CLASS]');
        $factory = new MongoDBRepositoryFactory($serializer, $client);

        $this->assertEquals($repository, $factory->create( 'broadway_test',  '[CLASS]'));
    }

    /**
     * @test
     */
    public function it_generate_the_right_collection()
    {
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $serializer = $this->getMockBuilder(Serializer::class)
            ->getMock();

        $client
            ->method('selectCollection')
            ->willReturn($collection);

        $mock = $this
            ->getMockBuilder(MongoDBRepositoryFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('getCollectionFromClass')
            ->with( $client, 'broadway_test', '[CLASS]');

        $reflectionClass = new \ReflectionClass(MongoDBRepositoryFactory::class);
        $constructor = $reflectionClass->getConstructor();
        $constructor->invoke($mock, $serializer, $client);

        $create = $reflectionClass->getMethod('create');
        $create->invoke($mock, 'broadway_test', '[CLASS]');
    }

    /**
     * @test
     */
    public function it_return_a_collection_from_factory_method()
    {
        $serializer = $this->getMockBuilder(Serializer::class)
            ->getMock();

        $collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client
            ->method('selectCollection')
            ->with('broadway_database_test', 'NamespacedClass')
            ->willReturn($collection);


        $factory = new MongoDBRepositoryFactory($serializer, $client);

        $this->assertEquals($collection, $factory->getCollectionFromClass($client, 'broadway_database_test', 'This\\Is\\A\\NamespacedClass'));
    }
}
