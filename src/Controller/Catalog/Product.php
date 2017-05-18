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
use TeamNeustaGmbh\Magentypo\Block\Catalog\Product\ListRendererInterface;
use TeamNeustaGmbh\Magentypo\Block\Catalog\Product\RendererInterface;

/**
 * Class Product
 *
 * @package TeamNeustaGmbh\Magentypo\Controller\Catalog
 */
class Product extends Action
{
    const DEFAULT_RENDERER = 'default';

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
        $productIds = $this->request->getParam('id');
        $productIds = explode(',', $productIds);

        $renderer = $this->getProductRendererBlock(
            $this->request->getParam('type', 'default')
        );

        if ($renderer === false) {
            $this->sendResponse(400, 'Invalid type given');
            return;
        }

        switch (count($productIds)) {
            case 0:
                $this->handleNoProduct();
                break;
            case 1:
                /** @noinspection PhpParamsInspection */
                $this->handleSingleProduct($renderer, $productIds[0]);
                break;
            default:
                /** @noinspection PhpParamsInspection */
                $this->handleMultipleProducts($renderer, $productIds);
        }
    }

    /**
     * Handles rendering of no products
     *
     * @return void
     */
    private function handleNoProduct()
    {
        $this->sendResponse(400, 'No product id given');
    }

    /**
     * Handles rendering of a single product
     *
     * @param RendererInterface|ListRendererInterface $renderer
     * @param int                                     $productId
     *
     * @return void
     */
    private function handleSingleProduct($renderer, $productId)
    {
        if ($renderer instanceof RendererInterface) {
            $renderer->setProductId($productId);
            $this->sendResponse(200, $renderer->render());
        } else if ($renderer instanceof ListRendererInterface) {
            $renderer->setProductIds([$productId]);
            $this->sendResponse(200, $renderer->render());
        } else {
            $this->sendResponse(
                400,
                'Renderer for single product must implement ReaderInterface or ListReaderInterface'
            );
        }
    }

    /**
     * Handles rendering of multiple products
     *
     * @param ListRendererInterface $renderer
     * @param array                 $productIds
     *
     * @return void
     */
    private function handleMultipleProducts($renderer, array $productIds)
    {
        if ($renderer instanceof ListRendererInterface) {
            $renderer->setProductIds($productIds);
            $this->sendResponse(200, $renderer->render());
        } else {
            $this->sendResponse(400, 'Renderer for multiple products must implement ListReaderInterface');
        }
    }

    /**
     * Sends the response directly
     *
     * @param int    $statusCode
     * @param string $body
     *
     * @return void
     */
    private function sendResponse(int $statusCode, string $body)
    {
        /** @var Http $response */
        $response = $this->getResponse();

        $response->setHttpResponseCode($statusCode)
            ->setBody($body)
            ->sendResponse();
    }

    /**
     * @param string $type
     *
     * @return AbstractBlock|false
     */
    private function getProductRendererBlock($type = null)
    {
        /** @var \Magento\Framework\View\Result\Page $page */
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        return $page->getLayout()->getBlock('m2t3.product.renderer.' . strtolower($type));
    }

}
