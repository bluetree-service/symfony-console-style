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

    public function testTimer(): void
    {
        $this->style->timer();
        $this->assertRegExp('/\[ 000000000.00[\d]{2} \]/', $this->getDisplay());

        $this->style->timer(true);
        $this->assertRegExp('/\[ 000000000.00[\d]{2} \]\n/', $this->getDisplay());
    }

    public function testFormatSection(): void
    {
        $this->style->formatSection('Section', 'Format Section info (default)');
        $this->assertEquals("[Section] Format Section info (default)\n", $this->getDisplay());
    }
}
