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

namespace TeamNeustaGmbh\Magentypo\Test\Unit\Framework\View\Page\Config;

use TeamNeustaGmbh\Magentypo\Framework\View\Page\Config\Renderer;

class RenderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    private $objectManager;

    /**
     * @var \TeamNeustaGmbh\Magentypo\Framework\View\Page\Config\Renderer
     */
    private $renderer;

    /**
     *
     */
    public function setUp()
    {
        $pageTitleMock = $this->getMockBuilder('\\Magento\\Framework\\View\\Page\\Title')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock()
        ;

        $assetGroupMock = $this->getMockBuilder('\\Magento\\Framework\\View\\Asset\\GroupedCollection')
            ->disableOriginalConstructor()
            ->setMethods(['getGroups'])
            ->getMock()
        ;

        $assetGroupMock->method('getGroups')
            ->willReturn([]);

        $pageConfig = $this->getMockBuilder("\\Magento\\Framework\\View\\Page\\Config")
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock()
        ;

        $pageConfig->method('getMetaData')
            ->willReturn(['title' => 'Test-Titel']);

        $pageConfig->method('getTitle')
            ->willReturn($pageTitleMock);

        $pageConfig->method('getAssetCollection')
            ->willReturn($assetGroupMock);

        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->renderer = $this->objectManager->getObject('\TeamNeustaGmbh\Magentypo\Framework\View\Page\Config\Renderer',['pageConfig' => $pageConfig]);
    }

    /**
     * @test
     * @return void
     */
    public function removeMetaAndTitleFromResultWhenRequestIsOfTypeMagentypo()
    {
        $matches = [];
        preg_match('/(.*)(Test-Titel)(.*)/',$this->renderer->renderHeadContent(),$matches);

        self::assertFalse(count($matches) == 0);
    }

    /**
     * @test
     * @return void
     */
    public function getMetaAndTitleFromResultOnNoneMagentypoRequest()
    {
        $matches = [];
        preg_match('/(.*)(Test-Titel)(.*)/',$this->renderer->renderHeadContent(true),$matches);

        self::assertTrue(count($matches) == 0);
    }
}