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
$style->genericBlock('Generic block success', 'blue', '', 50);

$style->formatBlock(['Error 1', 'Error 2'], 'error');
$style->formatBlock(['Error 1', 'Error 2'], 'info');
$style->errorLine(['Error Line']);

$style->formatSection('Section', 'Format Section info (default)');
$style->formatSection('Section', 'Format Section error', 'error');
$style->formatSection('Section', 'Format Section comment', 'comment');
$style->formatSection('Section', 'Format Section question', 'question');

$style->setAlign(5);
$style->infoMessage('Alignment 5');
$style->setAlign(10);
$style->okMessage('Alignment 10');
$style->setAlign(15);
$style->warningMessage('Alignment 15');

$message = 'This is a very long message, which should be truncated';
$style->truncateLn($message, 7);
$style->truncateLn($message, -5);
$style->truncateLn($message, 7, '!!');
$style->truncateLn('Short', 10);

//timer display enable
$style->newLine(4);

$style->toggleShowTimer();

$style->timer();

$style->warningMessage('Warning message with timer');
$style->errorMessage('Error message with timer');
$style->okMessage('Ok message with timer');
$style->infoMessage('Info message with timer');

$style->newLine(2);
$style->error('Error with timer');
$style->note('Note with timer');
$style->warning('Warning with timer');
$style->caution('Caution with timer');
$style->success('Success with timer');

$style->genericBlock('Generic block success', 'blue', 'success', 50);
$style->genericBlock('Generic block success', 'blue', '', 50);

$style->formatBlock(['Error 1', 'Error 2'], 'error');
$style->formatBlock(['Error 1', 'Error 2'], 'info');
$style->errorLine(['Error Line']);

$style->formatSection('Section', 'Format Section info (default)');
$style->formatSection('Section', 'Format Section error', 'error');
$style->formatSection('Section', 'Format Section comment', 'comment');
$style->formatSection('Section', 'Format Section question', 'question');

$style->setAlign(5);
$style->infoMessage('Alignment 5');
$style->setAlign(10);
$style->okMessage('Alignment 10');
$style->setAlign(15);
$style->warningMessage('Alignment 15');

//display datetime
$style->newLine(4);

$style->toggleTimerType();

$style->warningMessage('Warning message with timer');
$style->errorMessage('Error message with timer');
$style->okMessage('Ok message with timer');
$style->infoMessage('Info message with timer');
