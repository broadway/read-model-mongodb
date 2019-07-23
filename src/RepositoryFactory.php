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
use MongoDB\Client;

/**
 * RepositoryFactory.
 *
 * @author Simon Barbier <simon@howaboutsales.com>
 */
interface RepositoryFactory
{
    /**
     * @param Client $client
     * @param string $databaseName
     * @param string $class
     *
     * @return Repository
     */
    public function create(Client $client, string $databaseName, string $class): Repository;
}