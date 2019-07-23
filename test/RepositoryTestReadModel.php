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

use Broadway\ReadModel\SerializableReadModel;

/**
 * RepositoryTestReadModel class.
 *
 * @author Simon Barbier <simon@howaboutsales.com>
 */
class RepositoryTestReadModel implements SerializableReadModel
{
    private $_id;
    private $name;
    private $foo;
    private $array;

    /**
     * @param mixed  $_id
     * @param string $name
     * @param mixed  $foo
     * @param array  $array
     */
    public function __construct($_id, string $name, $foo, array $array)
    {
        $this->_id = (string) $_id;
        $this->name = $name;
        $this->foo = $foo;
        $this->array = $array;
    }

    public function getId(): string
    {
        return $this->_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getFoo()
    {
        return $this->foo;
    }

    public function getArray(): array
    {
        return $this->array;
    }

    public function serialize(): array
    {
        return get_object_vars($this);
    }

    public static function deserialize(array $data)
    {
        return new self($data['_id'], $data['name'], $data['foo'], $data['array']);
    }
}
