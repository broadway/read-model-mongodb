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
 * AnotherReadModel class.
 *
 * @author Simon Barbier <simon@howaboutsales.com>
 */
class AnotherReadModel implements SerializableReadModel
{
    private $_id;

    public function __construct($_id)
    {
        $this->_id = (string) $_id;
    }

    public static function deserialize(array $data)
    {
        return new self($data['_id']);
    }

    public function serialize(): array
    {
        return ['_id' => $this->_id];
    }

    public function getId(): string
    {
        return $this->_id;
    }
}