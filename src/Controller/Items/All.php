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

namespace TeamNeustaGmbh\M2T3\Controller\Items;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Layout;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class All
 *
 * @package TeamNeustaGmbh\M2T3\Controller\Index
 */
class All extends Action
{
    const MODULE_MINICART = "minicart";

    /**
     * @var PageFactory
     */
    private $_resultPageFactory;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * Index constructor.
     *
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        ini_set('display_errors',1);
        $this->request = $context->getRequest();
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Dispatch request
     *
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        /** @var Layout $layout */
        $layout = $page->getLayout();
        $content = "";
        $module = $this->request->getParam('magentypo');
        switch ($module) {
            case self::MODULE_MINICART:
                $content = $this->getMiniCart($layout);
                break;
        }

        $content .= $this->getMagentoInitScripts($layout);

        $this->getResponse()->setBody($content);

        return;
    }

    private function getMagentoInitScripts(Layout $layout)
    {
        $magentoInitScripts = "";
        $magentoInitScripts .= $layout->getBlock('customer.section.config')->toHtml();
        $magentoInitScripts .= $layout->getBlock('customer.customer.data')->toHtml();
        return $magentoInitScripts;
    }

    /**
     * Load MiniCart from Layout and add as Content to response.
     *
     * @param Layout $layout
     *
     * @return string rendered HTML
     */
    private function getMiniCart(Layout $layout)
    {
        $miniCartHtml = $layout->getBlock('minicart')->toHtml();
        return $miniCartHtml;
    }
}
