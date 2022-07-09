<?php
declare(strict_types=1);

namespace Application\Component\Console\Command\IncrementVersionCommand;

use Symfony\Component\Console\Command\Command as ParentCommand;

abstract class AbstractCommand extends ParentCommand
{
    protected string $path  = '';

    protected string $set   = '';

    protected bool $major = false;

    protected bool $minor = false;

    protected bool $patch = false;

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

    protected function getMajor(): bool
    {
        return $this->major;
    }

    protected function setMajor(bool $major): self
    {
        $this->major = $major;

        return $this;
    }

    protected function getMinor(): bool
    {
        return $this->minor;
    }

    protected function setMinor(bool $minor): self
    {
        $this->minor = $minor;

        return $this;
    }

    protected function getPatch(): bool
    {
        return $this->patch;
    }

    protected function setPatch(bool $patch): self
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
