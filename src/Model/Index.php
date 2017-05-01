<?php
/**
 * This file is part of the TeamNeustaGmbH/m2t3 package.
 *
 * Copyright (c) 2017 neusta GmbH | Ein team neusta Unternehmen
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source
 * code.
 *
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */

namespace TeamNeustaGmbh\Magentypo\Model;

use TeamNeustaGmbh\Magentypo\Model\Elasticsearch\ConfigInterface;

/**
 * Class Index
 *
 * @package TeamNeustaGmbh\Magentypo\Model
 */
class Index
{
    const INDEX_NAME = 'magentypo';
    const PRODUCT_INDEX_NAME = 'magentypo_products';
    const CATEGORY_INDEX_NAME = 'magentypo_categories';

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var \Elastica\Index
     */
    private $index;

    /**
     * @var \Elastica\Client
     */
    private $client;

    /**
     * Index constructor.
     *
     * @param ConfigInterface $config
     * @param string|null     $host
     * @param int|null        $port
     *
     * @throws \Elastica\Exception\InvalidException
     * @throws \Elastica\Exception\ResponseException
     */
    public function __construct(ConfigInterface $config, $host = null, $port = null)
    {
        $this->host = $host ?? $config->getHost();
        $this->port = $port ?? $config->getPort();

        $this->_init();
        $this->_createIndex();
        $this->_createMapping();
    }

    /**
     * Initialize elasticsearch client
     */
    private function _init()
    {
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        $this->client = new \Elastica\Client([
            'host' => $this->host,
            'port' => $this->port,
        ]);
    }

    /**
     * Create index on elasticsearch server
     *
     * @throws \Elastica\Exception\InvalidException
     * @throws \Elastica\Exception\ResponseException
     */
    private function _createIndex()
    {
        $this->index = $this->client->getIndex(self::INDEX_NAME);

        $indexConfig = [
            'number_of_shards'   => 4,
            'number_of_replicas' => 1,
            'analysis'           => [
                'analyzer' => [
                    'default_index'  => [
                        'type'      => 'custom',
                        'tokenizer' => 'standard',
                        'filter'    => ['lowercase', 'mySnowball'],
                    ],
                    'default_search' => [
                        'type'      => 'custom',
                        'tokenizer' => 'standard',
                        'filter'    => ['standard', 'lowercase', 'mySnowball'],
                    ],
                ],
                'filter'   => [
                    'mySnowball' => [
                        'type'     => 'snowball',
                        'language' => 'German',
                    ],
                ],
            ],
        ];

        $this->index->create($indexConfig, true);
    }

    /**
     * Create mapping and send it to the elasticsearch server
     */
    private function _createMapping()
    {
        $elasticaType = $this->index->getType(self::INDEX_NAME);

        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        $mapping = new \Elastica\Type\Mapping();
        $mapping->setType($elasticaType);

        $mapping->setProperties([
            'id'   => ['type' => 'integer', 'include_in_all' => false],
            'name' => ['type' => 'string', 'include_in_all' => true],
            'sku'  => ['type' => 'string', 'include_in_all' => true],
        ]);

        // Send mapping to type
        $mapping->send();
    }
}
