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

namespace TeamNeustaGmbh\Magentypo\Controller\Catalog;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

/**
 * Class Product
 *
 * @package TeamNeustaGmbh\Magentypo\Controller\Catalog
 */
class Product extends Action
{
    /**
     * @var PageFactory
     */
    private $_resultPageFactory;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * View constructor.
     *
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        $this->request = $context->getRequest();
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $page->getLayout();
        $productIds = $this->request->getParam('id');

        $productIds = explode(',', $productIds);

        if (count($productIds) > 1) {
            $this->getResponse()->setBody("multiple ids not supported yet.")->sendResponse();
            return;
        }
        $productTile = $layout->getBlock('product_tile')->setProduct($productIds[0])->toHtml();
        $this->getResponse()->setBody($productTile)->sendResponse();
    }

}
