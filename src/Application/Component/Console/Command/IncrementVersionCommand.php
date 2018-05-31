<?php
declare(strict_types=1);

namespace Application\Component\Console\Command;

use Application\Exception\RuntimeException;
use Application\VersionFile\VersionFile;
use Application\VersionString\VersionString;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class IncrementVersionCommand extends AbstractCommand
{
    use LockableTrait;

    protected function configure(): self
    {
        $this->setName('increment-version');

        $description = sprintf('Increment the %s file', VersionFile::VERSION_FILE);
        $this->setDescription($description);

        // <editor-fold desc="InputOption: (string) path">

        $name        = 'path';
        $shortcut    = null;
        $mode        = InputOption::VALUE_REQUIRED;
        $description = sprintf('Path in which %s is located.', VersionFile::VERSION_FILE);
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

        // <editor-fold desc="InputOption: (int) major">

        $name        = 'major';
        $shortcut    = null;
        $mode        = InputOption::VALUE_NONE;
        $description = 'Increment the minor number';

        $this->addOption($name, $shortcut, $mode, $description);

        // </editor-fold>

        // <editor-fold desc="InputOption: (int) minor">

        $name        = 'minor';
        $shortcut    = null;
        $mode        = InputOption::VALUE_NONE;
        $description = 'Increment the minor number';

        $this->addOption($name, $shortcut, $mode, $description);

        // </editor-fold>

        // <editor-fold desc="InputOption: (int) patch">

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
        $description = sprintf('Initialize %s.', VersionFile::VERSION_FILE);

        $this->addOption($name, $shortcut, $mode, $description);

        // </editor-fold>

        return $this;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): self
    {
        if (!$this->lock()) {
            $message = 'The script is already running in another process.';
            throw new RuntimeException($message);
        }

        return $this;
    }

    protected function interact(InputInterface $input, OutputInterface $output): self
    {
        $versionString = new VersionString();

        $path = (string) $input->getOption('path');
        $path = trim($path);
        if (!is_readable($path)) {
            $message = 'The "--path" option is missing or invalid.';
            throw new RuntimeException($message);
        }
        $this->setPath(realpath($path));

        $init = (bool) $input->getOption('init');
        $this->setInit($init);

        $set = (string) $input->getOption('set');
        $set = trim($set);
        if (strlen($set) > 0 && !$versionString->isValid($set)) {
            $format  = 'The "--set" option contains an invalid semantic version ("%s").';
            $message = sprintf($format, $set);
            throw new RuntimeException($message);
        }
        $this->setSet($set);

        $major = (int) $input->getOption('major');
        $this->setMajor($major);

        $minor = (int) $input->getOption('minor');
        $this->setMinor($minor);

        $patch = (int) $input->getOption('patch');
        $this->setPatch($patch);

        return $this;
    }

    protected function execute(InputInterface $input, OutputInterface $output): self
    {
        $versionString = new VersionString();
        $versionFile   = new VersionFile();

        $filename = $this->getPath() . DIRECTORY_SEPARATOR . VersionFile::VERSION_FILE;

        if ($this->getInit()) {
            if (is_readable($filename)) {
                unlink($filename);
            }
            $versionFile->write($filename, $versionString->getInitial());
        }

        if (!is_readable($filename)) {
            $format  = '%s does not exist. Use option --init to initialize project.';
            $message = sprintf($format, $filename);
            throw new RuntimeException($message);
        }

        $buffer = $this->getSet();
        if (strlen($buffer) > 0) {
            $versionFile->write($filename, $buffer);
        }

        $buffer       = $versionFile->read($filename);
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

        return $this;
    }
}
