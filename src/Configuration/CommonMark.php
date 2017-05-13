<?php

namespace Elazar\Scribing\Configuration;

use Auryn\Injector;
use Elazar\Auryn\Configuration\ConfigurationInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlRenderer;
use League\CommonMark\Environment;

class CommonMark implements ConfigurationInterface
{
    public function __invoke(Injector $injector)
    {
        $injector->alias(
            ElementRendererInterface::class,
            HtmlRenderer::class
        );

        $injector->delegate(
            Environment::class,
            [Environment::class, 'createCommonMarkEnvironment']
        );
    }
}
