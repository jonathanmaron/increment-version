<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Application\Console\Command\IncrementVersion;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new IncrementVersion());
$application->run();