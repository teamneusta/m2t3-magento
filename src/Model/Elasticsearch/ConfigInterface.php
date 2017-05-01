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

/**
 * Interface ConfigInterface
 * @package TeamNeustaGmbh\Magentypo\Model\Elasticsearch
 */
interface ConfigInterface
{
    /**
     * Returns the default host of the elasticsearch server
     *
     * @api
     *
     * @return string
     */
    public function getHost();

    /**
     * Returns the default port of the elasticsearch server
     *
     * @api
     *
     * @return int
     */
    public function getPort();
}
