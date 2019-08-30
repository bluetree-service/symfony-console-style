<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Tester\TesterTrait;
use BlueConsole\Style;

class StyleTest extends TestCase
{
    use TesterTrait;

    /**
     * @var Style
     */
    protected $style;

    public function setUp(): void
    {
        $input = new ArgvInput;
        $this->output = new StreamOutput(fopen('php://memory', 'w+', false));
        $formatter = new FormatterHelper;
        $this->style = new Style($input, $this->output, $formatter);
    }

    public function testWarningMessage(): void
    {
        $this->style->warningMessage('Warning message');
        $this->assertEquals($this->getDisplay(), "[WARNING]            Warning message\n");
    }

    public function testErrorMessage(): void
    {
        $this->style->errorMessage('Error message');
        $this->assertEquals($this->getDisplay(), "[ERROR]              Error message\n");
    }

    public function testOkMessage(): void
    {
        $this->style->okMessage('Ok message');
        $this->assertEquals($this->getDisplay(), "[OK]                 Ok message\n");
    }

    public function testInfoMessage(): void
    {
        $this->style->infoMessage('Info message');
        $this->assertEquals($this->getDisplay(), "[INFO]               Info message\n");
    }
}
