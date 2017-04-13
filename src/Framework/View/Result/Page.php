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

namespace TeamNeustaGmbh\M2T3\Framework\View\Result;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework;
use Magento\Framework\View;

/**
 * Class Page
 */
class Page extends View\Result\Page
{
    /**
     * Determines Request forces SSI Header.
     *
     * @var bool
     */
    private $isMagentypoHeadRequest = false;

    /**
     * Determines Request forces SSI Body Class.
     *
     * @var bool
     */
    private $isMagentypoBodyClassRequest = false;

    /**
     * Template File for SSI Header Request.
     *
     * @var string
     */
    private $typoTemplate;

    /**
     * Template File for SSI Body Class Request.
     *
     * @var string
     */
    private $typoBodyClassTemplate;

    /**
     * Constructor
     *
     * @param View\Element\Template\Context       $context
     * @param View\LayoutFactory                  $layoutFactory
     * @param View\Layout\ReaderPool              $layoutReaderPool
     * @param Framework\Translate\InlineInterface $translateInline
     * @param View\Layout\BuilderFactory          $layoutBuilderFactory
     * @param View\Layout\GeneratorPool           $generatorPool
     * @param View\Page\Config\RendererFactory    $pageConfigRendererFactory
     * @param View\Page\Layout\Reader             $pageLayoutReader
     * @param string                              $template
     * @param string                              $typoTemplate
     * @param string                              $typoBodyClassTemplate
     * @param bool                                $isIsolated
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        View\Element\Template\Context $context,
        View\LayoutFactory $layoutFactory,
        View\Layout\ReaderPool $layoutReaderPool,
        Framework\Translate\InlineInterface $translateInline,
        View\Layout\BuilderFactory $layoutBuilderFactory,
        View\Layout\GeneratorPool $generatorPool,
        View\Page\Config\RendererFactory $pageConfigRendererFactory,
        View\Page\Layout\Reader $pageLayoutReader,
        $template,
        $typoTemplate,
        $typoBodyClassTemplate,
        $isIsolated = false
    ) {
        $this->request = $context->getRequest();
        $this->assetRepo = $context->getAssetRepository();
        $this->logger = $context->getLogger();
        $this->urlBuilder = $context->getUrlBuilder();
        $this->pageConfig = $context->getPageConfig();
        $this->pageLayoutReader = $pageLayoutReader;
        $this->viewFileSystem = $context->getViewFileSystem();
        $this->pageConfigRendererFactory = $pageConfigRendererFactory;
        $this->template = $template;
        $this->typoTemplate = $typoTemplate;
        $this->typoBodyClassTemplate = $typoBodyClassTemplate;
        parent::__construct(
            $context,
            $layoutFactory,
            $layoutReaderPool,
            $translateInline,
            $layoutBuilderFactory,
            $generatorPool,
            $pageConfigRendererFactory,
            $pageLayoutReader,
            $template,
            $isIsolated
        );
        $this->initPageConfigReader();
        if($this->request->getParam('magentypo') == 'head' ) {
            $this->isMagentypoHeadRequest = true;
        }
        if($this->request->getParam('magentypo') == 'body-class' ) {
            $this->isMagentypoBodyClassRequest = true;
        }
    }

    /**
     * @param ResponseInterface $response
     * @return $this
     */
    protected function render(ResponseInterface $response)
    {
        $this->pageConfig->publicBuild();
        if ($this->getPageLayout()) {
            $config = $this->getConfig();
            $this->addDefaultBodyClasses();
            $addBlock = $this->getLayout()->getBlock('head.additional'); // todo
            $requireJs = $this->getLayout()->getBlock('require.js');

            $this->assign([
                'requireJs' => $requireJs ? $requireJs->toHtml() : null,
                'headContent' => $this->pageConfigRenderer->renderHeadContent($this->isMagentypoHeadRequest),
                'headAdditional' => $addBlock ? $addBlock->toHtml() : null,
                'htmlAttributes' => $this->pageConfigRenderer->renderElementAttributes($config::ELEMENT_TYPE_HTML),
                'headAttributes' => $this->pageConfigRenderer->renderElementAttributes($config::ELEMENT_TYPE_HEAD),
                'bodyAttributes' => $this->pageConfigRenderer->renderElementAttributes($config::ELEMENT_TYPE_BODY),
                'loaderIcon' => $this->getViewFileUrl('images/loader-2.gif'),
            ]);

            $output = $this->getLayout()->getOutput();
            $this->assign('layoutContent', $output);
            $output = $this->renderPage();
            $this->translateInline->processResponseBody($output);
            $response->appendBody($output);
        } else {
            parent::render($response);
        }
        return $this;
    }

    /**
     * Render page template
     *
     * @return string
     * @throws \Exception
     */
    protected function renderPage()
    {
        // begin mod to get head only
        $template = $this->template;

        if($this->isMagentypoHeadRequest ) {
            $template = $this->typoTemplate;
        }
        if($this->isMagentypoBodyClassRequest){
            $template = $this->typoBodyClassTemplate;
        }
        $fileName = $this->viewFileSystem->getTemplateFileName($template);
        // end mod to get head only
        if (!$fileName) {
            throw new \InvalidArgumentException('Template "' . $template . '" is not found');
        }

        ob_start();
        try {
            extract($this->viewVars, EXTR_SKIP);
            include $fileName;
        } catch (\Exception $exception) {
            ob_end_clean();
            throw $exception;
        }
        $output = ob_get_clean();
        return $output;
    }
}
