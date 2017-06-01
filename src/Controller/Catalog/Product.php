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
 *
 * @author  Julian Nuß <j.nuss@neusta.de>
 */

namespace TeamNeustaGmbh\Magentypo\Controller\Catalog;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\App\Action\Context;
use TeamNeustaGmbh\Magentypo\Block\Catalog\Product\RendererInterface;

/**
 * Class Product
 *
 * @package TeamNeustaGmbh\Magentypo\Controller\Catalog
 */
class Product extends Action
{
    const DEFAULT_RENDERER = 'default';
    const ERROR_MESSAGE_NO_PRODUCT_ID = 'No product id given. You must provide at least 1 product id (parameter: id)';

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * View constructor.
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->request = $context->getRequest();
        parent::__construct($context);
    }

    /**
     * Dispatch request
     */
    public function execute()
    {
        $productIds = $this->getProductIdsFromRequest();

        if (count($productIds) === 0) {
            $this->renderResponse(400, self::ERROR_MESSAGE_NO_PRODUCT_ID);
            return;
        }

        $this->renderBlock($this->getProductRendererBlock(), $productIds);
    }

    /**
     * Calls the render method of the RendererInterface and sends a respond with code 200
     *
     * @param RendererInterface $renderer
     * @param \int[]            $productIds
     */
    private function renderBlock(RendererInterface $renderer, $productIds)
    {
        $renderer->setProductIds($productIds);
        $this->renderResponse(200, $renderer->render());
    }

    /**
     * Sends a response with given `statusCode` and `body` to the client
     *
     * @param int    $statusCode
     * @param string $body
     */
    private function renderResponse($statusCode, $body)
    {
        /** @var Http $response */
        $response = $this->getResponse();

        $response->setHttpResponseCode($statusCode)
            ->setBody($body)
            ->sendResponse();
    }

    private function getProductIdsFromRequest()
    {
        $productIds = $this->request->getParam('id');
        $productIds = explode(',', $productIds);

        $result = [];
        foreach ($productIds as $productId) {
            if (is_numeric($productId) && (string)(int)$productId === $productId) {
                $result[] = (int)$productId;
            }
        }

        return $result;
    }

    /**
     * @return AbstractBlock|RendererInterface|false
     */
    private function getProductRendererBlock()
    {
        $type = $this->request->getParam('type', 'default');

        /** @var \Magento\Framework\View\Result\Page $page */
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        return $page->getLayout()->getBlock('m2t3.product.renderer.' . strtolower($type));
    }

}
