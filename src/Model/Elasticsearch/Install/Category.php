<?php
/**
 * This file is part of the TeamNeustaGmbH/m2t3 package.
 *
 * Copyright (c) 2017 neusta GmbH | Ein team neusta Unternehmen
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */

namespace TeamNeustaGmbh\Magentypo\Model\Elasticsearch\Install;

use Elastica\Client;
use Elastica\Type\Mapping;
use TeamNeustaGmbh\Magentypo\Model\Elasticsearch\Resource;

/**
 * Class Category
 *
 * @package TeamNeustaGmbh\Magentypo\Model\Elasticsearch\Install
 */
class Category
{
    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var Client
     */
    private $client;

    /**
     * CustomerGroup constructor.
     *
     * @param Resource $resource
     */
    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
        $this->client = $this->resource->getClient();
    }

    /**
     * @return \Elastica\Type
     */
    public function createIndex()
    {
        $index = $this->client->getIndex(Resource::INDEX_NAME, true);
        if (!$index->exists()) {
            $index->create(
                [
                    'number_of_shards'   => 4,
                    'number_of_replicas' => 1
                ]
            );
        }

        $elasticType = $index->getType("category");

        $mapping = new Mapping();
        $mapping->setType($elasticType);

        $mapping->setProperties([
            'id'      => ['type' => 'integer', 'include_in_all' => FALSE],
            'name'    => ['type' => 'string', 'include_in_all' => TRUE],
            'path'    => ['type' => 'string', 'include_in_all' => TRUE]
        ]);

        // Send mapping to type
        $mapping->send();

        return $elasticType;
    }
}