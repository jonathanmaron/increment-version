<?php
declare(strict_types=1);

namespace Application\VersionString;

use Naneau\SemVer\Parser;
use Throwable;

class VersionString
{
    public const VERSION_SEPARATOR = '.';

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

    public function versionStringToVersionArray(string $versionString): array
    {
        $parser = Parser::parse($versionString);

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

    public function versionArrayToVersionString(array $versionArray): string
    {
        return implode(self::VERSION_SEPARATOR, $versionArray);
    }
}
