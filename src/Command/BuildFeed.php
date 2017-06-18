<?php

namespace Elazar\Scribing\Command;

use Elazar\Scribing\Content\FeedContentGenerator;
use Elazar\Scribing\Filesystem\MarkdownFileFilterIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildFeed extends Command
{
    /**
     * @var FeedContentGenerator
     */
    private $feedContentGenerator;

    /**
     * @param FeedContentGenerator $feedContentGenerator
     */
    public function __construct(
        FeedContentGenerator $feedContentGenerator
    ) {
        parent::__construct();

        $this->feedContentGenerator = $feedContentGenerator;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('build:feed')
            ->setDescription('Builds feed')
            ->setHelp('This command builds a post feed that can be consumed by a feed reader')
            ->addOption('feedTitle', null, InputOption::VALUE_REQUIRED, 'Title for the feed')
            ->addOption('feedLink', null, InputOption::VALUE_REQUIRED, 'Canonical URL for the feed')
            ->addArgument('sourcePath', InputArgument::REQUIRED, 'Directory containing Markdown files')
            ->addArgument('destinationPath', InputArgument::REQUIRED, 'Directory to write HTML files to');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sourceFiles = new MarkdownFileFilterIterator($input->getArgument('sourcePath'));
        $feedTitle = $input->getOption('feedTitle');
        $feedLink = $input->getOption('feedLink');
        $destinationPath = $input->getArgument('destinationPath');

        $this->feedContentGenerator->generate(
            $sourceFiles,
            $feedTitle,
            $feedLink,
            $destinationPath
        );
    }
}
