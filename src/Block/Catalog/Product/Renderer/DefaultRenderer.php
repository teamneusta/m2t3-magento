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

namespace TeamNeustaGmbh\Magentypo\Block\Catalog\Product\Renderer;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Helper\Output;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use TeamNeustaGmbh\Magentypo\Block\Catalog\Product\RendererInterface;

/**
 * Class DefaultRenderer
 * @package TeamNeustaGmbh\Magentypo\Block\Catalog\Product\Renderer
 */
class DefaultRenderer extends AbstractProduct implements RendererInterface
{
    const DEFAULT_TEMPLATE = 'TeamNeustaGmbh_Magentypo::catalog/product/view.phtml';

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * @var \int[]
     */
    private $productIds;

    /**
     * @var Output
     */
    private $output;

    /**
     * @param Context           $context
     * @param ProductRepository $productRepository
     * @param UrlHelper         $urlHelper
     * @param Output            $output
     * @param array             $data
     */
    public function __construct(
        Context $context,
        ProductRepository $productRepository,
        UrlHelper $urlHelper,
        Output $output,
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        $this->urlHelper = $urlHelper;
        $this->output = $output;

        $data['cache_lifetime'] = null;

        parent::__construct($context, $data);
    }

    public function setProductIds(int ...$productIds)
    {
        $this->productIds = $productIds;
    }

    public function getProductIds() : array
    {
        return $this->productIds;
    }

    public function render(): string
    {
        return $this->toHtml();
    }

    /**
     * Load product from repository and return it
     *
     * @param int $productId
     *
     * @return ProductInterface
     *
     * @throws NoSuchEntityException
     */
    public function getProductById(int $productId): ProductInterface
    {
        $cacheKey = 'product_' . $productId;

        if (!$this->hasData($cacheKey)) {
            $product = $this->productRepository->getById($productId);
            $this->setData($cacheKey, $product);
        }

        return $this->getData($cacheKey);
    }

    /**
     * Get post parameters
     *
     * @param Product $product
     *
     * @return array
     */
    public function getAddToCartPostParams(Product $product): array
    {
        $url = $this->getAddToCartUrl($product);

        $urlEncodedParam = ActionInterface::PARAM_NAME_URL_ENCODED;

        return [
            'action' => $url,
            'data'   => [
                'product'        => $product->getEntityId(),
                $urlEncodedParam => $this->urlHelper->getEncodedUrl($url),
            ],
        ];
    }

    /**
     * Returns the template if configured, else the default.
     *
     * @return string
     */
    public function getTemplate() : string
    {
        return $this->getData('template') ?? self::DEFAULT_TEMPLATE;
    }

    /**
     * Refactored this call from the template to the block, to make the dependency explicit.
     *
     * @param $product
     * @param $attributeHtml
     * @param $attribute
     *
     * @return string
     */
    public function renderProductAttribute($product, $attributeHtml, $attribute)
    {
        return $this->output->productAttribute($product, $attributeHtml, $attribute);
    }
}
