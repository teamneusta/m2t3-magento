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

namespace TeamNeustaGmbh\Magentypo\Block\Catalog\Product;

use Magento\Customer\Model\Context as CustomerContext;

/**
 * Class View
 *
 * @package TeamNeustaGmbh\Magentypo\Block\Catalog\Product
 */
class ProductList extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * Products count
     *
     * @var int
     */
    protected $_productsCount;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $_productRepository;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;


    /**
     * Product Id
     *
     * @var integer
     */
    private $_productId;

    /**
     * View constructor.
     *
     * @param \Magento\Catalog\Block\Product\Context   $context
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \Magento\Framework\App\Http\Context      $httpContext
     * @param array                                    $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        array $data = []
    ) {
        $this->_productRepository = $productRepository;
        $this->httpContext = $httpContext;
        $this->urlHelper = $urlHelper;
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setProduct($id)
    {
        $this->_productId = $id;
        return $this;
    }

    /**
     * Get block cache life time
     *
     * @return int
     */
    public function getCacheLifetime()
    {
        return null;
    }

    /**
     * Prepare collection with new products
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _beforeToHtml()
    {
        $this->setData('product', $this->_getProduct());
        return parent::_beforeToHtml();
    }

    /**
     * Prepare and return product
     *
     * @return \Magento\Catalog\Model\Product
     */
    protected function _getProduct()
    {
        $product = $this->_productRepository->getById($this->_productId);
        return $product;
    }

    /**
     * Get post parameters
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }
}