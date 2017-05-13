<?php

namespace Elazar\Scribing\Content;

use League\Plates\Engine;
use Symfony\Component\Console\Input\InputInterface;

class EngineFactory
{
    public function getEngine(InputInterface $input)
    {
        $engine = new Engine;
        $engine->setDirectory($input->getArgument('templatePath'));
        if ($input->hasOption('templateData')) {
            $data = require $input->getOption('templateData');
            $engine->addData($data);
        }
        return $engine;
    }
}
