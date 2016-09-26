<?php

namespace Application\Component\Console\Command;

use Naneau\SemVer\Parser as SemVerParser;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends AbstractCommand
{

    protected function configure()
    {
        $this->setName('increment-version.php');

        $this->setDescription('Increment the ' . self::VERSION_FILE . ' file');

        $this->addArgument(
            'path',
            InputArgument::REQUIRED,
            'Path in which ' . self::VERSION_FILE . ' is located.'
        );

        $this->addOption(
            'init',
            null,
            InputOption::VALUE_NONE,
            'Initialize ' . self::VERSION_FILE . '.'
        );

        $this->addOption(
            'set',
            null,
            InputOption::VALUE_REQUIRED,
            'Version number to set',
            null
        );

        $this->addOption(
            'major',
            null,
            InputOption::VALUE_NONE,
            'Increment the major number'
        );

        $this->addOption(
            'minor',
            null,
            InputOption::VALUE_NONE,
            'Increment the minor number'
        );

        $this->addOption(
            'patch',
            null,
            InputOption::VALUE_NONE,
            'Increment the patch number'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');

        if (!is_dir($path)) {
            throw new RuntimeException("{$path} does not exist.");
        }

        $filename = realpath($path) . DIRECTORY_SEPARATOR . self::VERSION_FILE;

        if ($input->getOption('init')) {
            if (is_readable($filename)) {
                unlink($filename);
            }
            $buffer = '0' . self::VERSION_SEPARATOR
                    . '0' . self::VERSION_SEPARATOR
                    . '1';
            $this->writeVersionFile($filename, $buffer);
        }

        // -------------------------------------------------------------------------------------------------------------

        if (!is_readable($filename)) {
            throw new RuntimeException("{$filename} does not exist. Use option --init to initialize project.");
        }

        // -------------------------------------------------------------------------------------------------------------

        if ($buffer = $input->getOption('set')) {
            $this->writeVersionFile($filename, $buffer);
        }

        // -------------------------------------------------------------------------------------------------------------

        $buffer  = $this->readVersionFile($filename);

        $version = $this->versionStringToVersionArray($buffer);

        if ($input->getOption('major')) {
            $version['major']++;
        }

        if ($input->getOption('minor')) {
            $version['minor']++;
        }

        if ($input->getOption('patch')) {
            $version['patch']++;
        }

        // -------------------------------------------------------------------------------------------------------------

        $buffer = $this->versionArrayToVersionString($version);

        $this->writeVersionFile($filename, $buffer);

        $output->writeln("Version is {$buffer}");

        // -------------------------------------------------------------------------------------------------------------

    }

}