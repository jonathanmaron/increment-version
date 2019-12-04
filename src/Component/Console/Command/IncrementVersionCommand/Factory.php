<?php
declare(strict_types=1);

namespace Application\Component\Console\Command\IncrementVersionCommand;

use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

class Factory
{
    public function __invoke(
        ?ContainerInterface $container = null,
        ?string $requestedName = null,
        ?array $options = null
    ): Command {

        return new $requestedName;
    }
}
