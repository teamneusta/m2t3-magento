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

namespace TeamNeustaGmbh\M2T3\Model\Elasticsearch\Install;

use Elastica\Client;
use Elastica\Type\Mapping;
use Magento\Framework\Webapi\Exception;
use TeamNeustaGmbh\M2T3\Model\Elasticsearch\Resource;

/**
 * Class CustomerGroup
 *
 * @package TeamNeustaGmbh\M2T3\Model\Elasticsearch\Install
 */
class CustomerGroup
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
        $elasticType = $index->getType("customer_group");

        $mapping = new Mapping();
        $mapping->setType($elasticType);

        $mapping->setProperties(
            [
                'id'   => ['type' => 'integer', 'include_in_all' => false],
                'name' => ['type' => 'string', 'include_in_all' => true]
            ]
        );

        // Send mapping to type
        $mapping->send();

        return $elasticType;
    }
}