#!/usr/bin/env php
<?php

namespace SymfonyStyle\Examples;

require_once __DIR__ . '/../vendor/autoload.php';

use BlueConsole\Style;
use Symfony\Component\Console\{
    Input\ArgvInput,
    Output\ConsoleOutput,
    Helper\FormatterHelper,
};

$input = new ArgvInput;
$output = new ConsoleOutput;
$formatter = new FormatterHelper;
$style = new Style($input, $output, $formatter);

$style->warningMessage('Warning message');
