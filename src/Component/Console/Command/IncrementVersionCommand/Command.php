<?php
declare(strict_types=1);

namespace Application\Component\Console\Command\IncrementVersionCommand;

use Application\Exception\RuntimeException;
use Application\VersionFile\VersionFile;
use Application\VersionString\VersionString;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends AbstractCommand
{
    use LockableTrait;

    protected function configure(): void
    {
        $this->setName('increment-version');

        $this->setDescription('Increment the version file');

        // <editor-fold desc="InputOption: (string) path">

        $name        = 'path';
        $shortcut    = null;
        $mode        = InputOption::VALUE_REQUIRED;
        $description = 'Path in which version file is located.';
        $default     = '';

        $this->addOption($name, $shortcut, $mode, $description, $default);

        // </editor-fold>

        // <editor-fold desc="InputOption: (string) set">

        $name        = 'set';
        $shortcut    = null;
        $mode        = InputOption::VALUE_REQUIRED;
        $description = 'Version number to set';
        $default     = '';

        $this->addOption($name, $shortcut, $mode, $description, $default);

        // </editor-fold>

        // <editor-fold desc="InputOption: (bool) major">

        $name        = 'major';
        $shortcut    = null;
        $mode        = InputOption::VALUE_NONE;
        $description = 'Increment the minor number';

        $this->addOption($name, $shortcut, $mode, $description);

        // </editor-fold>

        // <editor-fold desc="InputOption: (bool) minor">

        $name        = 'minor';
        $shortcut    = null;
        $mode        = InputOption::VALUE_NONE;
        $description = 'Increment the minor number';

        $this->addOption($name, $shortcut, $mode, $description);

        // </editor-fold>

        // <editor-fold desc="InputOption: (bool) patch">

        $name        = 'patch';
        $shortcut    = null;
        $mode        = InputOption::VALUE_NONE;
        $description = 'Increment the patch number';

        $this->addOption($name, $shortcut, $mode, $description);

        // </editor-fold>

        // <editor-fold desc="InputOption: (bool) init">

        $name        = 'init';
        $shortcut    = null;
        $mode        = InputOption::VALUE_NONE;
        $description = 'Initialize version file.';

        $this->addOption($name, $shortcut, $mode, $description);

        // </editor-fold>
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        if (!$this->lock()) {
            $message = 'The script is already running in another process.';
            throw new RuntimeException($message);
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $versionString = new VersionString();

        $path = $input->getOption('path');
        assert(is_string($path));
        if (!is_readable($path)) {
            $message = 'The "--path" option is missing or invalid.';
            throw new RuntimeException($message);
        }
        $realpath = realpath($path);
        assert(is_string($realpath));
        $this->setPath($realpath);

        $init = $input->getOption('init');
        assert(is_bool($init));
        $this->setInit($init);

        $set = $input->getOption('set');
        assert(is_string($set));
        $set = trim($set);
        if (strlen($set) > 0 && !$versionString->isValid($set)) {
            $format  = 'The "--set" option contains an invalid semantic version "%s".';
            $message = sprintf($format, $set);
            throw new RuntimeException($message);
        }
        $this->setSet($set);

        $major = $input->getOption('major');
        assert(is_bool($major));
        $this->setMajor($major);

        $minor = $input->getOption('minor');
        assert(is_bool($minor));
        $this->setMinor($minor);

        $patch = $input->getOption('patch');
        assert(is_bool($patch));
        $this->setPatch($patch);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $versionString = new VersionString();
        $versionFile   = new VersionFile();

        $filename = $versionFile->getFilename($this->getPath());

        if ($this->getInit()) {
            $versionFile->write($filename, $versionString->getInitial());
        }

        if (!$versionFile->isValid($filename)) {
            $format  = '%s does not exist. Use option --init to initialize project.';
            $message = sprintf($format, $filename);
            throw new RuntimeException($message);
        }

        $buffer = $this->getSet();
        if (strlen($buffer) > 0) {
            $versionFile->write($filename, $buffer);
        }

        $buffer = $versionFile->read($filename);
        if (!$versionString->isValid($buffer)) {
            $format  = 'The version file "%s" contains an invalid semantic version "%s".';
            $message = sprintf($format, $filename, $buffer);
            throw new RuntimeException($message);
        }
        $versionArray = $versionString->versionStringToVersionArray($buffer);

        if ($this->getMajor()) {
            $versionArray['major']++;
        }

        if ($this->getMinor()) {
            $versionArray['minor']++;
        }

        if ($this->getPatch()) {
            $versionArray['patch']++;
        }

        $buffer = $versionString->versionArrayToVersionString($versionArray);
        $versionFile->write($filename, $buffer);

        $format  = 'Version is %s';
        $message = sprintf($format, $buffer);
        $output->writeln($message);

        return 0;
    }
}
