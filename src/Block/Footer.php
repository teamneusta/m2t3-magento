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

/**
 * Created by PhpStorm.
 * User: tom
 * Date: 30.10.2016
 * Time: 21:59
 */

namespace TeamNeustaGmbh\Magentypo\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class Footer
 *
 * @package TeamNeustaGmbh\Magentypo\Block
 */
class Footer  extends Template
{
    /**
     * @return string
     */
    public function getSsiRoute()
    {
        return '<!--#include virtual="/?footer=1" -->';
    }
}
