<?php

/**
 * This file is part of the BRM package.
 *
 * Copyright (c) HowAboutSales
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Broadway\ReadModel\MongoDB;

use Broadway\ReadModel\Repository;
use PHPUnit\Framework\TestCase;

/**
 * RepositoryTestCase class.
 *
 * @author Simon Barbier <simon@howaboutsales.com>
 *
 *
 * this class is a copy of Broadway\ReadModel\Testing\RepositoryTestCase but has they made createReadModel private, i couldn't override the RepositoryTestReadModel class
 * i needed to override that class because in mongo, id should be _id
 */
abstract class RepositoryTestCase extends TestCase
{
    /** @var MongoDBRepository */
    protected $repository;

    /** @var MongoDBRepository */
    protected $anotherRepository;

    protected function setUp()
    {
        $this->createRepository();
        $this->anotherRepository = $this->createUniqueRepository('another_test_collection', AnotherReadModel::class);
    }

    abstract protected function createUniqueRepository(string $collectionName, string $readModelClass): Repository;

    protected function createRepository(): Repository
    {
        $this->repository = $this->createUniqueRepository('test', RepositoryTestReadModel::class);
        return $this->repository;
    }




    /*
     *
     * BEGIN COPY TEST CASE FROM Broadway\ReadModel\Testing\RepositoryTestCase
     *
     */

    /**
     * @test
     */
    public function it_saves_and_finds_read_models_by_id()
    {
        $model = $this->createReadModel('1', 'othillo', 'bar');

        $this->repository->save($model);

        $this->assertEquals($model, $this->repository->find(1));
    }

    /**
     * @test
     */
    public function it_saves_and_finds_read_models_with_a_value_object_id()
    {
        $id = new TestReadModelId('42');
        $model = $this->createReadModel($id, 'othillo', 'bar');

        $this->repository->save($model);

        $this->assertEquals($model, $this->repository->find($id));
    }

    /**
     * @test
     */
    public function it_returns_null_if_not_found_on_empty_repo()
    {
        $this->assertEquals(null, $this->repository->find(2));
    }

    /**
     * @test
     */
    public function it_returns_null_if_not_found()
    {
        $model = $this->createReadModel('1', 'othillo', 'bar');

        $this->repository->save($model);

        $this->assertNull($this->repository->find(2));
    }

    /**
     * @test
     */
    public function it_finds_by_name()
    {
        $model1 = $this->createReadModel('1', 'othillo', 'bar');
        $model2 = $this->createReadModel('2', 'asm89', 'baz');

        $this->repository->save($model1);
        $this->repository->save($model2);

        $this->assertEquals([$model1], $this->repository->findBy(['name' => 'othillo']));
        $this->assertEquals([$model2], $this->repository->findBy(['name' => 'asm89']));
    }

    /**
     * @test
     */
    public function it_finds_by_one_element_in_array()
    {
        $model1 = $this->createReadModel('1', 'othillo', 'bar', ['elem1', 'elem2']);
        $model2 = $this->createReadModel('2', 'asm89', 'baz', ['elem3', 'elem4']);

        $this->repository->save($model1);
        $this->repository->save($model2);

        $this->assertEquals([$model1], $this->repository->findBy(['array' => 'elem1']));
        $this->assertEquals([$model2], $this->repository->findBy(['array' => 'elem4']));
    }

    /**
     * @test
     */
    public function it_finds_if_all_clauses_match()
    {
        $model1 = $this->createReadModel('1', 'othillo', 'bar');
        $model2 = $this->createReadModel('2', 'asm89', 'baz');

        $this->repository->save($model1);
        $this->repository->save($model2);

        $this->assertEquals([$model1], $this->repository->findBy(['name' => 'othillo', 'foo' => 'bar']));
        $this->assertEquals([$model2], $this->repository->findBy(['name' => 'asm89', 'foo' => 'baz']));
    }

    /**
     * @test
     */
    public function it_does_not_find_when_one_of_the_clauses_doesnt_match()
    {
        $model1 = $this->createReadModel('1', 'othillo', 'bar');
        $model2 = $this->createReadModel('2', 'asm89', 'baz');

        $this->repository->save($model1);
        $this->repository->save($model2);

        $this->assertEquals([], $this->repository->findBy(['name' => 'othillo', 'foo' => 'baz']));
        $this->assertEquals([], $this->repository->findBy(['name' => 'asm89', 'foo' => 'bar']));
    }

    /**
     * @test
     */
    public function it_returns_empty_array_when_found_nothing()
    {
        $model1 = $this->createReadModel('1', 'othillo', 'bar');
        $model2 = $this->createReadModel('2', 'asm89', 'baz');

        $this->repository->save($model1);
        $this->repository->save($model2);

        $this->assertEquals([], $this->repository->findBy(['name' => 'Jan']));
    }

    /**
     * @test
     */
    public function it_returns_empty_array_when_searching_for_empty_array()
    {
        $model = $this->createReadModel('1', 'othillo', 'bar');

        $this->repository->save($model);

        $this->assertEquals([], $this->repository->findBy([]));
    }

    /**
     * @test
     */
    public function it_removes_a_readmodel()
    {
        $model = $this->createReadModel('1', 'John', 'Foo', ['foo' => 'bar']);
        $this->repository->save($model);

        $this->repository->remove('1');

        $this->assertEquals([], $this->repository->findAll());
    }

    /**
     * @test
     */
    public function it_removes_a_read_model_using_a_value_object_as_its_id()
    {
        $id = new TestReadModelId('175');

        $model = $this->createReadModel($id, 'Bado', 'Foo', ['foo' => 'bar']);
        $this->repository->save($model);

        $this->repository->remove($id);

        $this->assertEquals([], $this->repository->findAll());
    }

    /**
     * @test
     */
    public function it_returns_all_read_models()
    {
        $model1 = $this->createReadModel('1', 'othillo', 'bar');
        $model2 = $this->createReadModel('2', 'asm89', 'baz');
        $model3 = $this->createReadModel('3', 'edelprino', 'baz');

        $this->repository->save($model1);
        $this->repository->save($model2);
        $this->repository->save($model3);

        $this->assertEquals([$model1, $model2, $model3], $this->repository->findAll());
    }

    /*
     *
     * END COPY TEST CASE FROM Broadway\ReadModel\Testing\RepositoryTestCase
     *
     */






    /**
     * @test
     */
    public function it_saves_and_finds_read_models_by_id_with_the_right_class()
    {
        $model = $this->createReadModel('1', 'othillo', 'bar');

        $this->repository->save($model);

        $this->assertEquals($model, $this->repository->find(1));
        $this->assertInstanceOf(RepositoryTestReadModel::class, $this->repository->find(1));
    }

    /**
     * @test
     * @expectedException Assert\InvalidArgumentException
     */
    public function it_prevents_wrong_read_model()
    {
        $model = new AnotherReadModel('99');
        $this->repository->save($model);
    }

    /**
     * @test
     */
    public function it_returns_the_right_read_model_class_and_good_segregation()
    {
        $model1 = $this->createReadModel('1', 'othillo', 'bar');
        $model2 = new AnotherReadModel('99');
        $model3 = new AnotherReadModel('98');

        $this->repository->save($model1);
        $this->anotherRepository->save($model2);
        $this->anotherRepository->save($model3);

        $this->assertInstanceOf(RepositoryTestReadModel::class, $this->repository->find(1));
        $this->assertNull($this->repository->find(99));
        $this->assertNull($this->repository->find(98));
        $this->assertEquals('test', $this->repository->getCollection()->getCollectionName());

        $mongoDocuments = array_map(function ($doc) { return json_decode(json_encode($doc), true); }, $this->repository->getCollection()->find([])->toArray());
        $this->assertCount(1, $mongoDocuments);
        $this->assertEquals("1", $mongoDocuments[0]['_id']);


        $this->assertInstanceOf(AnotherReadModel::class, $this->anotherRepository->find(99));
        $this->assertInstanceOf(AnotherReadModel::class, $this->anotherRepository->find(98));
        $this->assertNull($this->anotherRepository->find(1));
        $this->assertEquals('another_test_collection', $this->anotherRepository->getCollection()->getCollectionName());

        $mongoDocuments = array_map(function ($doc) { return json_decode(json_encode($doc), true); }, $this->anotherRepository->getCollection()->find([])->toArray());
        $this->assertCount(2, $mongoDocuments);
        $this->assertEquals("99", $mongoDocuments[0]['_id']);
        $this->assertEquals("98", $mongoDocuments[1]['_id']);
    }

    /**
     * @test
     */
    public function it_find_by_id_with_findby()
    {
        $model1 = $this->createReadModel('1', 'othillo', 'bar');
        $model2 = $this->createReadModel('2', 'asm89', 2);

        $this->repository->save($model1);
        $this->repository->save($model2);

        $this->assertEquals([$model1], $this->repository->findBy(['_id' => '1']));
        $this->assertEquals([$model1], $this->repository->findBy(['_id' => 1]));
        $this->assertEquals([$model1], $this->repository->findBy(['_id' => 1, 'foo' => 'bar']));
        $this->assertEquals([], $this->repository->findBy(['_id' => 1, 'foo' => 'baz']));

        $this->assertEquals([$model2], $this->repository->findBy(['_id' => '2']));
        $this->assertEquals([$model2], $this->repository->findBy(['_id' => 2, 'foo' => 2]));
        $this->assertEquals([], $this->repository->findBy(['_id' => 1, 'foo' => '2']));

        $this->assertEquals([], $this->repository->findBy(['id' => '1']));
    }

    private function createReadModel($id, $name, $foo, array $array = [])
    {
        return new RepositoryTestReadModel($id, $name, $foo, $array);
    }
}

class TestReadModelId
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }
}

