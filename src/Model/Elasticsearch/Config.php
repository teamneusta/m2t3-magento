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

namespace TeamNeustaGmbh\Magentypo\Model\Elasticsearch;

use Magento\Framework\App\DeploymentConfig;

/**
 * Class Config
 */
final class Config implements ConfigInterface
{
    const CONFIG_PREFIX = 'elastic/default/';

    const DEFAULT_HOST = 'elasticsearch';
    const DEFAULT_PORT = 9200;

    /**
     * @var DeploymentConfig
     */
    private $config;

    /**
     * Config constructor.
     *
     * @param DeploymentConfig $config
     */
    public function __construct(DeploymentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Returns the default host of the elasticsearch server
     *
     * @return string
     */
    public function getHost()
    {
        return (string)($this->getConfig('host') ?? self::DEFAULT_HOST);
    }

    /**
     * Returns the default port of the elasticsearch server
     *
     * @return int
     */
    public function getPort()
    {
        return (int)($this->getConfig('port') ?? self::DEFAULT_PORT);
    }

    /**
     * Returns a config value for a given name with prepended prefix.
     *
     * @param string $name
     *
     * @return mixed
     */
    private function getConfig($name)
    {
        return $this->config->get(self::CONFIG_PREFIX.$name, null);
    }
}
