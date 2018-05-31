<?php
declare(strict_types=1);

namespace Application\Component\Console\Command;

use Naneau\SemVer\Parser as SemVerParser;
use Symfony\Component\Console\Command\Command as ParentCommand;
use Symfony\Component\Filesystem\Filesystem;
use Throwable;

abstract class AbstractCommand extends ParentCommand
{
    const VERSION_FILE      = '.version';

    const VERSION_SEPARATOR = '.';

    protected $path  = '';

    protected $set   = '';

    protected $init  = false;

    protected $major = 0;

    protected $minor = 0;

    protected $patch = 0;

    protected function isValidVersionString(string $string): bool
    {
        $ret = true;

        try {
            SemVerParser::parse($string);
        } catch (Throwable $e) {
            $ret = false;
        }

        return $ret;
    }

    protected function versionStringToVersionArray(string $string): array
    {
        $parser = SemVerParser::parse($string);

        if ($parser->hasBuild() || $parser->hasPreRelease()) {
            $message = 'Only numeric semantic version numbers in the format "0.0.0" are supported.';
            throw new RuntimeException($message);
        }

        return [
            'major' => $parser->getMajor(),
            'minor' => $parser->getMinor(),
            'patch' => $parser->getPatch(),
        ];
    }

    protected function versionArrayToVersionString(array $array): string
    {
        return implode(self::VERSION_SEPARATOR, $array);
    }

    protected function readVersionFile(string $filename): string
    {
        $buffer = file_get_contents($filename);

        return trim($buffer);
    }

    protected function writeVersionFile(string $filename, string $buffer): bool
    {
        $filesystem = new Filesystem();

        $filesystem->dumpFile($filename, trim($buffer));

        return true;
    }

    protected function getPath(): string
    {
        return $this->path;
    }

    protected function setPath(string $path): self
    {
        $this->path = $path;

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
}
