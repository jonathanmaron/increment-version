<?php
declare(strict_types=1);

namespace Application\VersionString;

use Naneau\SemVer\Parser;
use Throwable;

class VersionString
{
    /**
     * Seperator between semantic version parts
     *
     * @var string
     */
    private const VERSION_SEPARATOR = '.';

    /**
     * Convert a semantic version string to an array containing the major, minor and patch parts
     *
     * @param string $versionString
     *
     * @return array
     */
    public function versionStringToVersionArray(string $versionString): array
    {
        $parser = Parser::parse($versionString);

        return [
            'major' => (int) $parser->getMajor(),
            'minor' => (int) $parser->getMinor(),
            'patch' => (int) $parser->getPatch(),
        ];
    }

    /**
     * Convert an array containing the major, minor and patch parts to a semantic version string
     *
     * @param array $versionArray
     *
     * @return string
     */
    public function versionArrayToVersionString(array $versionArray): string
    {
        return implode(self::VERSION_SEPARATOR, $versionArray);
    }

    /**
     * Return true, if $versionString contains a valid semantic version; false otherwise
     *
     * @param string $versionString
     *
     * @return bool
     */
    public function isValid(string $versionString): bool
    {
        $ret = false;

        try {
            $parser = Parser::parse($versionString);
            if (!$parser->hasBuild() && !$parser->hasPreRelease()) {
                $ret = true;
            }
        } catch (Throwable $e) {
        }

        return $ret;
    }

    /**
     * Return the initial semantic version
     *
     * @return string
     */
    public function getInitial(): string
    {
        return sprintf('0%s0%s1', self::VERSION_SEPARATOR, self::VERSION_SEPARATOR);
    }
}
