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
use Magento\Customer\Model\ResourceModel\Group\Collection as CustomerGroupCollection;
use Magento\Framework\Indexer\ActionInterface as IndexerInterface;
use Magento\Framework\Mview\ActionInterface as MviewInterface;

/**
 * Class CustomerGroups
 *
 * @package TeamNeustaGmbh\M2T3\Model\Indexer
 */
class CustomerGroup implements IndexerInterface, MviewInterface
{
    /**
     * @var CustomerGroupCollection
     */
    private $customerGroups;
    private $elasticaType;

    /**
     * CustomerGroups constructor.
     *
     * @param CustomerGroupCollection $customerGroups
     */
    public function __construct(CustomerGroupCollection $customerGroups, \TeamNeustaGmbh\M2T3\Model\Elasticsearch\Install\CustomerGroup $installer)
    {
        $this->customerGroups = $customerGroups;
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
        $groupDocuments = [];
        /** @var \Magento\Customer\Model\Group $group */
        foreach ($this->customerGroups->load() as $group){
            $id = $group->getCustomerGroupId();
            $groupDocuments[] = new \Elastica\Document($id,
                [
                    'id' => $id,
                    'name' => $group->getCustomerGroupCode()
                ]);
        }
        $elasticaType->addDocuments($groupDocuments);
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