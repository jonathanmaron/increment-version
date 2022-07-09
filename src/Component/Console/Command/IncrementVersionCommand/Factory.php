<?php
declare(strict_types=1);

namespace Application\Component\Console\Command\IncrementVersionCommand;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

class Factory
{
    public function __invoke(
        ?ContainerInterface $container = null,
        string $requestedName = '',
        array $options = []
    ): Command {
        $command = new $requestedName;
        assert($command instanceof AbstractCommand);
        return $command;
    }
}
