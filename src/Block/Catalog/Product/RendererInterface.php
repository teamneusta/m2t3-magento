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

/**
 * Interface RendererInterface
 * @package TeamNeustaGmbh\Magentypo\Block\Catalog\Product
 */
interface RendererInterface
{
    public function setProductIds(int ...$productId);

    public function getProductIds() : array;

    public function render(): string;
}
