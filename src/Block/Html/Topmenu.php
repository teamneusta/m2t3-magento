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

namespace TeamNeustaGmbh\Magentypo\Block\Html;
use Magento\Framework\View\Element\Template;

/**
 * Class Topmenu
 *
 * @package TeamNeustaGmbh\Magentypo\Block\Html
 */
class Topmenu extends Template
{
    /**
     * @return string
     */
    public function getSSiSnippet()
    {
        return '<!--#include virtual="/?header=1" -->';
    }
}