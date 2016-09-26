<?php

namespace Application\Component\Console\Command;

use Naneau\SemVer\Parser as SemVerParser;
use Symfony\Component\Console\Command\Command as SymfonyComponentConsoleCommandCommand;

abstract class AbstractCommand extends SymfonyComponentConsoleCommandCommand
{
    const VERSION_FILE      = '.version';
    const VERSION_SEPARATOR = '.';

    protected function versionStringToVersionArray($string)
    {
        $semVerParser = SemVerParser::parse($string);

        if ($semVerParser->hasBuild() || $semVerParser->hasPreRelease()) {
            throw new RuntimeException('Only numeric semantic version numbers in the format "0.0.0" are supported.');
        }

        return [
            'major' => $semVerParser->getMajor(),
            'minor' => $semVerParser->getMinor(),
            'patch' => $semVerParser->getPatch(),
        ];

    }

    protected function versionArrayToVersionString($array)
    {
        return implode(self::VERSION_SEPARATOR, $array);
    }

    protected function readVersionFile($filename)
    {
        $buffer = file_get_contents($filename);

        return trim($buffer);
    }

    protected function writeVersionFile($filename, $buffer)
    {
        $buffer = trim($buffer);

        return file_put_contents($filename, $buffer);
    }

}