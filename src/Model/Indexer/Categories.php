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

namespace TeamNeustaGmbh\Magentypo\Model\Indexer;

use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Framework\Indexer\ActionInterface as IndexerInterface;
use Magento\Framework\Mview\ActionInterface as MviewInterface;
use TeamNeustaGmbh\Magentypo\Model\Elasticsearch\Install\Category;

/**
 * Class Categories
 *
 * @package TeamNeustaGmbh\Magentypo\Model\Indexer
 */
class Categories implements IndexerInterface, MviewInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    private $categoryCollection;

    /**
     * @var \Elastica\Type
     */
    private $elasticaType;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $_storeManager;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection, Category $installer
    ) {
        $this->_storeManager = $storeManager;
        $this->categoryCollection = $categoryCollection;
        $this->elasticaType = $installer->createIndex();
    }
    /**
     * Reindex specific data identified by given ids.
     * @Todo implement based on changes on entity.
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
        $categoryDocuments = [];
        /** @var Collection $loadedCategoryCollection */
        $loadedCategoryCollection = $this->categoryCollection
            ->addAttributeToSelect('name')
            ->setStore($this->_storeManager->getStore())    // add StoreScope to get URLs
            ->load();
        /** @var \Magento\Catalog\Model\Category $category */
        foreach ($loadedCategoryCollection as $category){

            if ($category->getLevel() < 2) {
                continue;
            }

            $id = $category->getId();
            $categoryDocuments[] = new \Elastica\Document($id,
                [
                    'id' => $id,
                    'name' => $category->getName(),
                    'path' => $category->getPath(),
                    'url'   => $category->getUrl()
                ]);
        }
        if(count($categoryDocuments) > 0){
            $elasticaType->addDocuments($categoryDocuments);
            $elasticaType->getIndex()->refresh();
        }
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
