<?php

namespace Elazar\Scribing\Command;

use Elazar\Scribing\Content\EngineFactory;
use Elazar\Scribing\Content\MarkdownContentGenerator;
use Elazar\Scribing\Filesystem\MarkdownFileFilterIterator;
use Elazar\Scribing\Path\PagePathGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildPages extends Command
{
    /**
     * @var MarkdownContentGenerator
     */
    private $contentGenerator;

    /**
     * @var PagePathGenerator
     */
    private $pathGenerator;

    /**
     * @var EngineFactory
     */
    private $engineFactory;

    /**
     * @param MarkdownContentGenerator $contentGenerator
     * @param PagePathGenerator $pathGenerator
     * @param EngineFactory $engineFactory
     */
    public function __construct(
        MarkdownContentGenerator $contentGenerator,
        PagePathGenerator $pathGenerator,
        EngineFactory $engineFactory
    ) {
        parent::__construct();

        $this->contentGenerator = $contentGenerator;
        $this->pathGenerator = $pathGenerator;
        $this->engineFactory = $engineFactory;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('build:pages')
            ->setDescription('Builds static pages')
            ->setHelp('This command build pages that require only injecting content into a layout')
            ->setDefinition(new BuildInputDefinition);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sourceFiles = new MarkdownFileFilterIterator($input->getArgument('sourcePath'));
        $engine = $this->engineFactory->getEngine($input);
        $destinationPath = $input->getArgument('destinationPath');

        $this->contentGenerator->generate(
            $sourceFiles,
            $engine,
            $destinationPath,
            $this->pathGenerator
        );
    }
}
