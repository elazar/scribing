<?php

namespace Elazar\Scribing\Command;

use Elazar\Scribing\Content\ArchiveContentGenerator;
use Elazar\Scribing\Content\EngineFactory;
use Elazar\Scribing\Content\MarkdownContentGenerator;
use Elazar\Scribing\Filesystem\MarkdownFileFilterIterator;
use Elazar\Scribing\Path\PostPathGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildPosts extends Command
{
    /**
     * @var MarkdownContentGenerator
     */
    private $markdownContentGenerator;

    /**
     * @var ArchiveContentGenerator
     */
    private $archiveContentGenerator;

    /**
     * @var PostPathGenerator
     */
    private $pathGenerator;

    /**
     * @var EngineFactory
     */
    private $engineFactory;

    /**
     * @param MarkdownContentGenerator $markdownContentGenerator
     * @param ArchiveContentGenerator $archiveContentGenerator
     * @param PostPathGenerator $pathGenerator
     * @param EngineFactory $engineFactory
     */
    public function __construct(
        MarkdownContentGenerator $markdownContentGenerator,
        ArchiveContentGenerator $archiveContentGenerator,
        PostPathGenerator $pathGenerator,
        EngineFactory $engineFactory
    ) {
        parent::__construct();

        $this->markdownContentGenerator = $markdownContentGenerator;
        $this->archiveContentGenerator = $archiveContentGenerator;
        $this->pathGenerator = $pathGenerator;
        $this->engineFactory = $engineFactory;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('build:posts')
            ->setDescription('Builds posts')
            ->setHelp('This command build posts that require paths specific to their titles and dates')
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

        $this->markdownContentGenerator->generate(
            $sourceFiles,
            $engine,
            $destinationPath,
            $this->pathGenerator
        );

        $this->archiveContentGenerator->generate(
            $sourceFiles,
            $engine,
            $destinationPath
        );
    }
}
