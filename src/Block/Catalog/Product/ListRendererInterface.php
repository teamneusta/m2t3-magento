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

namespace TeamNeustaGmbh\Magentypo\Block\Catalog\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface ListRendererInterface
 * @package TeamNeustaGmbh\Magentypo\Block\Catalog\Product
 */
interface ListRendererInterface
{
    /**
     * Sets the ids of the products
     *
     * @param \int[] $productId
     *
     * @return void
     */
    public function setProductIds(array $productId);

    /**
     * Returns an array with products for the set ids
     *
     * @return array|ProductInterface[]
     *
     * @throws NoSuchEntityException
     */
    public function getProducts(): array;

    /**
     * Render product html.
     *
     * @return string
     */
    public function render(): string;
}
