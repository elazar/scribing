<?php

namespace Elazar\Scribing\Configuration;

use Auryn\Injector;
use Elazar\Auryn\Configuration\ConfigurationInterface;
use Elazar\Scribing\Command;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Application implements ConfigurationInterface
{
    private $commandClasses = [
        Command\BuildFeed::class,
        Command\BuildPages::class,
        Command\BuildPosts::class,
    ];

    public function __invoke(Injector $injector)
    {
        $injector->alias(InputInterface::class, ArgvInput::class);
        $injector->share(InputInterface::class);

        $injector->alias(OutputInterface::class, ConsoleOutput::class);
        $injector->share(OutputInterface::class);

        $injector->alias(LoggerInterface::class, ConsoleLogger::class);
        $injector->share(ConsoleLogger::class);

        $injector->define(ConsoleApplication::class, [':name' => 'Scribing']);
        $injector->prepare(ConsoleApplication::class, [$this, 'addCommands']);
    }

    public function addCommands(ConsoleApplication $application, Injector $injector)
    {
        foreach ($this->commandClasses as $commandClass) {
            $application->add($injector->make($commandClass));
        }
    }
}
