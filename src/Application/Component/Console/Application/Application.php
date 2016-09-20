<?php

namespace Application\Component\Console\Application;

use Application\Console\Command\IncrementVersion;
use Symfony\Component\Console\Application as ApplicationConsoleComponentSymfony;
use Symfony\Component\Console\Input\InputInterface;

class Application extends ApplicationConsoleComponentSymfony
{
    protected $config;

    protected function getCommandName(InputInterface $input)
    {
        return 'increment-version';
    }

    protected function getDefaultCommands()
    {
        $defaultCommands   = parent::getDefaultCommands();
        $defaultCommands[] = new IncrementVersion();

        return $defaultCommands;
    }

    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();

        return $inputDefinition;
    }

}