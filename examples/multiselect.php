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

//$list = [
//    ['first', 'second', 'last'],
//    [1, 2, 3, 4, 5],
//    ['a', 'b'],
//    [true, false, null],
//];
$list = ['first', 'second', 'last'];

//$multiSelect->renderMultiSelect($list);

$val = $multiSelect->renderSingleSelect($list);
dump($val);

//nested list support??
