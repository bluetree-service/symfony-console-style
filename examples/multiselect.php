#!/usr/bin/env php
<?php

namespace SymfonyStyle\Examples;

require_once __DIR__ . '/../vendor/autoload.php';

use BlueConsole\MultiSelect;
use Symfony\Component\Console\{
    Input\ArgvInput,
    Output\ConsoleOutput,
    Helper\FormatterHelper,
    Style\SymfonyStyle
};

$input = new ArgvInput;
$output = new ConsoleOutput;

$multiSelect = new MultiSelect(new SymfonyStyle($input, $output));

$list = ['first', 'second', 'last'];
$output->writeln('Data list:');

dump($list);
$output->writeln('');

$val = $multiSelect->renderMultiSelect($list);

$output->writeln('');
$output->writeln('Method output:');
dump($val);
