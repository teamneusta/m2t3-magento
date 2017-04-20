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

namespace TeamNeustaGmbh\Magentypo\Model;
use Magento\Framework\ObjectManager\ObjectManager;

/**
 * Class Index
 *
 * @package TeamNeustaGmbh\Magentypo\Model
 */
class Index
{
    const INDEX_NAME = "magentypo";
    const PRODUCT_INDEX_NAME = "magentypo_products";

    const CATEGORY_INDEX_NAME = "magentypo_categories";

    /**
     * Index constructor.
     */
    public function __construct(

    )
    {
        $this->client = new \Elastica\Client([
            'host' => 'elasticsearch',
            'port' => '9200'
        ]);

        $this->index =  $this->client->getIndex(self::INDEX_NAME, true);

        $this->index->create(
            array(
                'number_of_shards' => 4,
                'number_of_replicas' => 1,
                'analysis' => array(
                    'analyzer' => array(
                        'default_index' => array(
                            'type' => 'custom',
                            'tokenizer' => 'standard',
                            'filter' => array('lowercase', 'mySnowball')
                        ),
                        'default_search' => array(
                            'type' => 'custom',
                            'tokenizer' => 'standard',
                            'filter' => array('standard', 'lowercase', 'mySnowball')
                        )
                    ),
                    'filter' => array(
                        'mySnowball' => array(
                            'type' => 'snowball',
                            'language' => 'German'
                        )
                    )
                )
            ),
            true
        );

        $elasticaType = $this->index->getType(self::INDEX_NAME);

        $mapping = new \Elastica\Type\Mapping();
        $mapping->setType($elasticaType);

        $mapping->setProperties([
            'id'      => ['type' => 'integer', 'include_in_all' => FALSE],
            'name'    => ['type' => 'string', 'include_in_all' => TRUE],
            'sku'     => ['type' => 'string', 'include_in_all' => TRUE]
        ]);

// Send mapping to type
        $mapping->send();

    }
}
