<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Tester\TesterTrait;
use BlueConsole\Style;

class AdditionalTest extends TestCase
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

    public function testTruncate(): void
    {
        $this->style->truncate('1234567890', 5);
        $this->assertEquals('12345...', $this->getDisplay());
    }

    public function testTruncateWithSuffix(): void
    {
        $this->style->truncate('1234567890', 5, '_');
        $this->assertEquals('12345_', $this->getDisplay());
    }
}
