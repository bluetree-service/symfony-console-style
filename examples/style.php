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
$style->errorMessage('Error message');
$style->okMessage('Ok message');
$style->infoMessage('Info message');

$style->error('Error');
$style->note('Note');
$style->warning('Warning');
$style->caution('Caution');
$style->success('Success');

$style->genericBlock('Generic block success', 'blue', 'success', 50);

$style->formatBlock(['Error 1', 'Error 2'], 'error');
$style->errorLine(['Error Line']);

$style->formatSection('Section', 'Format Section info (default)');
$style->formatSection('Section', 'Format Section error', 'error');
$style->formatSection('Section', 'Format Section comment', 'comment');
$style->formatSection('Section', 'Format Section question', 'question');
