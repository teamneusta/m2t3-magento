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
namespace TeamNeustaGmbh\Magentypo\Test\Unit\Framework\View\Result;

class PageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $layoutFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $readerPoolMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $tranlateInlineMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $builderFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $generatorPoolMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $rendererFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $layoutReaderMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    public function setUp()
    {
        $this->contextMock = $this->getMockBuilder(\Magento\Framework\View\Element\Template\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->layoutFactoryMock = $this->getMockBuilder(\Magento\Framework\View\LayoutFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->readerPoolMock = $this->getMockBuilder(\Magento\Framework\View\Layout\ReaderPool::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->tranlateInlineMock = $this->getMockBuilder(\Magento\Framework\Translate\Inline::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->builderFactoryMock = $this->getMockBuilder(\Magento\Framework\View\Layout\BuilderFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->generatorPoolMock = $this->getMockBuilder(\Magento\Framework\View\Layout\GeneratorPool::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->rendererFactoryMock = $this->getMockBuilder(\Magento\Framework\View\Page\Config\RendererFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['renderHeadContent', 'create'])
            ->getMock();
        $this->layoutReaderMock = $this->getMockBuilder(\Magento\Framework\View\Page\Layout\Reader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $layoutMock = $this->getMockBuilder(\Magento\Framework\View\Layout::class)
            ->disableOriginalConstructor()
            ->getMock();
        $pageConfigMock = $this->getMockBuilder(\Magento\Framework\View\Page\Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPageLayout'])
            ->getMock();
        $this->requestMock = $this->getMockBuilder(\Magento\Framework\App\Request\Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParam'])
            ->getMock();

        $pageConfigMock->method('getPageLayout')
            ->willReturn(true);

        $this->contextMock->method('getLayout')
            ->willReturn($layoutMock);

        $this->contextMock->method('getPageConfig')
            ->willReturn($pageConfigMock);

        $this->contextMock->method('getRequest')
            ->willReturn($this->requestMock);
    }

    /**
     * @return \TeamNeustaGmbh\Magentypo\Framework\View\Result\Page
     */
    private function getConfiguredPage()
    {
        return new \TeamNeustaGmbh\Magentypo\Framework\View\Result\Page(
            $this->contextMock,
            $this->layoutFactoryMock,
            $this->readerPoolMock,
            $this->tranlateInlineMock,
            $this->builderFactoryMock,
            $this->generatorPoolMock,
            $this->rendererFactoryMock,
            $this->layoutReaderMock,
            'some_template.phtml',
            'typo_template.phtml',
            'typo-body-class.phtml',
            false
        );
    }

    /**
     * @test
     * @return void
     */
    public function testRenderPageWillThrowExceptionOnMissingTemplate()
    {
        $this->setExpectedException('InvalidArgumentException', 'Template "some_template.phtml" is not found');

        $viewFileSystemMock = $this->getMockBuilder(\Magento\Framework\View\FileSystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock->method('getViewFileSystem')
            ->willReturn($viewFileSystemMock);

        $page = $this->getConfiguredPage();

        $method = self::getMethod(\TeamNeustaGmbh\Magentypo\Framework\View\Result\Page::class, 'renderPage');

        $method->invoke($page);
    }

    /**
     * @return array
     */
    public function dataProviderTestTemplateBasedOnRequestParam()
    {
        return [
            'valid Magentoypo Request' => [
                'typo_template.phtml',
                [
                    'magentypo' => 'head',
                ]
            ],
            'regular Request' => [
                'some_template.phtml',
                [
                    'magentypo' => 'no_valid_value'
                ]
            ],
            'regular Request without params' => [
                'some_template.phtml',
                []
            ],
            'body-class Request' => [
                'typo-body-class.phtml',
                [
                    'magentypo' => 'body-class',
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderTestTemplateBasedOnRequestParam
     * @return void
     */
    public function testRenderPageWillUseTemplateBasedOnRequestParameter($template, $requestParameters)
    {
        $viewFileSystemMock = $this->getMockBuilder(\Magento\Framework\View\FileSystem::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTemplateFileName'])
            ->getMock();

        $viewFileSystemMock->method('getTemplateFileName')
            ->with($template)
            ->willReturn('valid_path');

        foreach ($requestParameters as $key => $value) {
            $this->requestMock->method('getParam')
                ->with($key)
                ->willReturn($value);
        }

        $this->contextMock->method('getViewFileSystem')
            ->willReturn($viewFileSystemMock);

        $page = $this->getConfiguredPage();

        $method = self::getMethod(\TeamNeustaGmbh\Magentypo\Framework\View\Result\Page::class, 'renderPage');

        $method->invoke($page, true);
    }

    /**
     * @param $class
     * @param $name
     * @param $property
     * @return \ReflectionMethod
     */
    protected static function getMethod($class, $name)
    {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
