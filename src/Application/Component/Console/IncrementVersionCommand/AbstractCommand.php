<?php
declare(strict_types=1);

namespace Application\Component\Console\IncrementVersionCommand;

use Symfony\Component\Console\Command\Command as ParentCommand;

abstract class AbstractCommand extends ParentCommand
{
    protected string $path  = '';

    protected string $set   = '';

    protected int $major = 0;

    protected int $minor = 0;

    protected int $patch = 0;

    protected bool $init  = false;

    protected function getPath(): string
    {
        return $this->path;
    }

    protected function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    protected function getSet(): string
    {
        return $this->set;
    }

    protected function setSet(string $set): self
    {
        $this->set = $set;

        return $this;
    }

    protected function getMajor(): int
    {
        return $this->major;
    }

    protected function setMajor(int $major): self
    {
        $this->major = $major;

        return $this;
    }

    protected function getMinor(): int
    {
        return $this->minor;
    }

    protected function setMinor(int $minor): self
    {
        $this->minor = $minor;

        return $this;
    }

    protected function getPatch(): int
    {
        return $this->patch;
    }

    protected function setPatch(int $patch): self
    {
        $this->patch = $patch;

        return $this;
    }

    protected function getInit(): bool
    {
        return $this->init;
    }

    protected function setInit(bool $init): self
    {
        $this->init = $init;

        return $this;
    }
}
