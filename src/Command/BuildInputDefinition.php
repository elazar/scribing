<?php

namespace Elazar\Scribing\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class BuildInputDefinition extends InputDefinition
{
    public function __construct()
    {
        parent::__construct([
            new InputArgument('sourcePath', InputArgument::REQUIRED, 'Directory containing Markdown files'),
            new InputArgument('destinationPath', InputArgument::REQUIRED, 'Directory to write HTML files to'),
            new InputArgument('templatePath', InputArgument::REQUIRED, 'Directory containing template files'),
            new InputOption('templateData', null, InputOption::VALUE_OPTIONAL, 'PHP file returning an array of data to send to templates'),
        ]);
    }
}
