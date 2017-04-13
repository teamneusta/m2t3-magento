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

namespace TeamNeustaGmbh\M2T3\Controller\Catalog;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;

/**
 * Created by PhpStorm.
 * User: Tom
 * Date: 27.01.17
 * Time: 13:43
 */
class Current extends Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    public function __construct(Context $context, \Magento\Customer\Model\Session $customerSession)
    {
        parent::__construct($context);
        $this->customerSession = $customerSession;
    }

    /**
     *
     */
    public function execute()
    {
        /** @var ResponseInterface $response */
        $response = $this->getResponse();
    }
}