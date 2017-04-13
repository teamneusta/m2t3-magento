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

namespace TeamNeustaGmbh\M2T3\Model\Elasticsearch;

use Elastica\Client;
use Magento\Framework\App\DeploymentConfig;

/**
 * Class Resource
 *
 * @package TeamNeustaGmbh\M2T3\Model\Elasticsearch
 */
class Resource
{
    /**
     * Index Name
     */
    const INDEX_NAME = "magentypo";

    /**
     * Elasticsearch Host
     *
     * @var string
     */
    private $host;

    /**
     * Elasticsearch Port
     *
     * @var integer
     */
    private $port;

    /**
     * @var \Elastica\Client
     */
    private $client;

    /**
     * Resource constructor.
     *
     * @param DeploymentConfig $config
     * @param $host
     * @param $port
     */
    public function __construct(
        DeploymentConfig $config,
        $host,
        $port
    )
    {
        $this->config = $config;
        $this->host = $host;
        $this->port = $port;

        if($envHost = $config->get('elastic/default/host', false)){
            $this->host = $envHost;
        }
        if($envPort = $config->get('elastic/default/port', false)){
            $this->port = $envPort;
        }

        $this->_init();
    }

    private function _init()
    {
        $this->client = new Client([
            'host' => $this->host,
            'port' => $this->port
        ]);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}