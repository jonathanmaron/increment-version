<?php
declare(strict_types=1);

namespace Application\VersionString;

use Application\Exception\RuntimeException;
use Naneau\SemVer\Parser;
use Throwable;

class VersionString
{
    private const VERSION_SEPARATOR = '.';

    public function versionStringToVersionArray(string $versionString): array
    {
        if (!$this->isValid($versionString)) {
            $format  = 'Invalid semantic version ("%s")';
            $message = sprintf($format, $versionString);
            throw new RuntimeException($message);
        }

        $parser = Parser::parse($versionString);

        if ($parser->hasBuild() || $parser->hasPreRelease()) {
            $format  = 'Only numeric semantic version numbers in the format "%s" are supported.';
            $message = sprintf($format, $this->getInitial());
            throw new RuntimeException($message);
        }

        return [
            'major' => $parser->getMajor(),
            'minor' => $parser->getMinor(),
            'patch' => $parser->getPatch(),
        ];
    }

    public function isValid(string $versionString): bool
    {
        $ret = true;

        try {
            Parser::parse($versionString);
        } catch (Throwable $e) {
            $ret = false;
        }

        return $ret;
    }

    public function getInitial(): string
    {
        $format = '0%s0%s1';
        $ret    = sprintf($format, self::VERSION_SEPARATOR, self::VERSION_SEPARATOR);

        return $ret;
    }

    public function versionArrayToVersionString(array $versionArray): string
    {
        return implode(self::VERSION_SEPARATOR, $versionArray);
    }
}
