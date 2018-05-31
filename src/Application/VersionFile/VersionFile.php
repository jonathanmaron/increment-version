<?php
declare(strict_types=1);

namespace Application\VersionFile;

class VersionFile
{
    public const VERSION_FILE = '.version';

    public function read(string $filename): string
    {
        $buffer = file_get_contents($filename);

        return trim($buffer);
    }

    public function write(string $filename, string $buffer): bool
    {
        file_put_contents($filename, trim($buffer));

        return true;
    }
}
