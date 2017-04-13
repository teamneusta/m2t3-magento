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

namespace TeamNeustaGmbh\M2T3\Model\Indexer;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\CatalogUrlRewrite\Model\ResourceModel\Category\ProductCollection;
use Magento\Framework\Indexer\ActionInterface as IndexerInterface;
use Magento\Framework\Mview\ActionInterface as MviewInterface;
use TeamNeustaGmbh\M2T3\Model\Elasticsearch\Install\Product;

/**
 * Class Products
 *
 * @package TeamNeustaGmbh\M2T3\Model\Indexer
 */
class Products implements IndexerInterface, MviewInterface
{
    /**
     * @var ProductCollection
     */
    private $productCollection;

    /**
     * @var \Elastica\Type
     */
    private $elasticaType;

    /**
     * Products constructor.
     *
     * @param ProductCollection $productCollection
     * @param Product           $installer
     */
    public function __construct(Collection $productCollection, Product $installer)
    {
        $this->productCollection = $productCollection;
        $this->elasticaType = $installer->createIndex();
    }
    /**
     * Reindex specific data identified by given ids.
     *
     * @param \int[] $ids
     */
    public function execute($ids)
    {
        // TODO: Implement execute() method.
    }

    /**
     * Reindex all Data.
     */
    public function executeFull()
    {
        // TODO: Implement executeFull() method.
        // TODO: Move this stuff to a better place
        $elasticaType = $this->elasticaType;
        $productDocuments = [];
        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($this->productCollection->addAttributeToSelect('name')->load() as $product){
            $id = $product->getId();
            $productDocuments[] = new \Elastica\Document($id,
                [
                    'id' => $id,
                    'name' => $product->getName(),
                    'sku' => $product->getSku()
                ]);
        }
        if(count($productDocuments) == 0){
            $productDocuments[] = new \Elastica\Document(1,
                [
                    'id' => 1,
                    'name' => 'Z-Wave Schalter dingsi',
                    'sku' => '0815abcd'
                ]);
        }
        $elasticaType->addDocuments($productDocuments);
        $elasticaType->getIndex()->refresh();
    }

    /**
     * What is it good for ?
     *  - absolute something... I guess
     *
     * @param array $ids
     */
    public function executeList(array $ids)
    {
        // TODO: Implement executeList() method.
    }

    /**
     * What is it good for ?
     *  - absolute something... I guess
     *
     * @param int $id
     */
    public function executeRow($id)
    {
        // TODO: Implement executeRow() method.
    }
}