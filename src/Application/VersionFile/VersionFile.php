<?php
declare(strict_types=1);

namespace Application\VersionFile;

class VersionFile
{
    /**
     * Name of file containing the semantic version
     *
     * @var string
     */
    private const VERSION_FILE = '.version';

    /**
     * Return the full filename of the version file
     *
     * @param string $path
     *
     * @return string
     */
    public function getFilename(string $path): string
    {
        return $path . DIRECTORY_SEPARATOR . self::VERSION_FILE;
    }

    /**
     * Return true, if the version file exists and is readable; false otherwise
     *
     * @param string $filename
     *
     * @return bool
     */
    public function isValid(string $filename): bool
    {
        return is_readable($filename);
    }

    /**
     * Read and return the semantic version from the version file
     *
     * @param string $filename
     *
     * @return string
     */
    public function read(string $filename): string
    {
        $buffer = file_get_contents($filename);

        return trim($buffer);
    }

    /**
     * Write the semantic version to the version file
     *
     * @param string $filename
     * @param string $buffer
     *
     * @return bool
     */
    public function write(string $filename, string $buffer): bool
    {
        file_put_contents($filename, trim($buffer));

        return true;
    }
}
