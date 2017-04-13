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

namespace TeamNeustaGmbh\M2T3\Framework\View\Page\Config;

class Renderer extends \Magento\Framework\View\Page\Config\Renderer
{
    /**
     * @param bool $isMagentypoRequest
     *
     * @return string
     */
    public function renderHeadContent($isMagentypoRequest = false)
    {
        $result = '';
        /** Added Magentypo Param to avoid rendering of meta and title data for magentypo request. */
        if(!$isMagentypoRequest) {
            $result .= $this->renderMetadata();
            $result .= $this->renderTitle();
        }
        $this->prepareFavicon();
        $result .= $this->renderAssets($this->getAvailableResultGroups());
        $result .= $this->pageConfig->getIncludes();
        return $result;
    }
}