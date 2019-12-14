<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Tester\TesterTrait;
use BlueConsole\Style;

class StyleMessageTest extends TestCase
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
        $this->assertEquals($this->getDisplay(), "[ WARN ]     Warning message\n");
    }

    public function testErrorMessage(): void
    {
        $this->style->errorMessage('Error message');
        $this->assertEquals($this->getDisplay(), "[ FAIL ]     Error message\n");
    }

    public function testOkMessage(): void
    {
        $this->style->okMessage('Ok message');
        $this->assertEquals($this->getDisplay(), "[  OK  ]     Ok message\n");
    }

    public function testInfoMessage(): void
    {
        $this->style->infoMessage('Info message');
        $this->assertEquals($this->getDisplay(), "[ INFO ]     Info message\n");
    }

    public function testToggleTimer(): void
    {
        $this->style->toggleShowTimer();

        $this->assertTrue($this->style->isTimerOn());
    }

    public function testDateFormatting(): void
    {
        $this->assertEquals('c', $this->style->getDateTimeFormat());
        $this->assertNull($this->style->setDateTimeFormat('d-m-Y'));
        $this->assertEquals('d-m-Y', $this->style->getDateTimeFormat());

        $this->assertEquals(14, $this->style->getTimeCharLength());
        $this->assertInstanceOf(Style::class, $this->style->setTimeCharLength(1));
        $this->assertEquals(1, $this->style->getTimeCharLength());
    }

    public function testWarningMessageWithTimer(): void
    {
        $this->style->toggleShowTimer();
        $this->style->warningMessage('Warning message');

        $this->assertRegExp($this->getMessageExpression('warning'), $this->getDisplay());
    }

    public function testOkMessageWithTimer(): void
    {
        $this->style->toggleShowTimer();
        $this->style->okMessage('Ok message');

        $this->assertRegExp($this->getMessageExpression('ok'), $this->getDisplay());
    }

    public function testErrorMessageWithTimer(): void
    {
        $this->style->toggleShowTimer();
        $this->style->errorMessage('Error message');

        $this->assertRegExp($this->getMessageExpression('error'), $this->getDisplay());
    }

    public function testInfoMessageWithTimer(): void
    {
        $this->style->toggleShowTimer();
        $this->style->infoMessage('Info message');

        $this->assertRegExp($this->getMessageExpression('info'), $this->getDisplay());
    }

    public function testWarningMessageWithDateTime(): void
    {
        $this->style->toggleShowTimer();
        $this->style->toggleTimerType();
        $this->style->warningMessage('Warning message');

        $this->assertRegExp($this->getMessageExpression('warning', true), $this->getDisplay());
    }

    public function testOkMessageWithDateTime(): void
    {
        $this->style->toggleShowTimer();
        $this->style->toggleTimerType();
        $this->style->okMessage('Ok message');

        $this->assertRegExp($this->getMessageExpression('ok', true), $this->getDisplay());
    }

    public function testErrorMessageWithDateTime(): void
    {
        $this->style->toggleShowTimer();
        $this->style->toggleTimerType();
        $this->style->errorMessage('Error message');

        $this->assertRegExp($this->getMessageExpression('error', true), $this->getDisplay());
    }

    public function testInfoMessageWithDateTime(): void
    {
        $this->style->toggleShowTimer();
        $this->style->toggleTimerType();
        $this->style->infoMessage('Info message');

        $this->assertRegExp($this->getMessageExpression('info', true), $this->getDisplay());
    }

    /**
     * @param string $type
     * @param bool $isDateTime
     * @return string
     */
    protected function getMessageExpression(string $type, bool $isDateTime = false): string
    {
        $begin = '/\[ 000000000.00[\d]{2} \]\[ ';

        if ($isDateTime) {
            $begin = '/\[ 2[\d]{3}-[\d]{2}-[\d]{2}T[\d]{2}:[\d]{2}:[\d]{2}\+[\d]{2}:[\d]{2} \]\[ ';
        }

        $middle = ' \][ ]{5}';
        $end = ' message\\n/';

        switch ($type) {
            case 'warning':
                return $begin . 'WARN' . $middle . 'Warning' . $end;
            case 'ok':
                return $begin . ' OK ' . $middle . 'Ok' . $end;
            case 'error':
                return $begin . 'FAIL' . $middle . 'Error' . $end;
            case 'info':
                return $begin . 'INFO' . $middle . 'Info' . $end;

            default:
                return '';
        }
    }
}
