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

    public function testTruncateMessage(): void
    {
        $message = $this->style->truncateMessage('1234567890', 5);
        $this->assertEquals('12345...', $message);

        $message = $this->style->truncateMessage('1234567890', 5, '-');
        $this->assertEquals('12345-', $message);
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

    public function testTruncateLn(): void
    {
        $this->style->truncateLn('1234567890', 5);
        $this->assertEquals("12345...\n", $this->getDisplay());
    }

    public function testAlignGrtSet(): void
    {
        $this->assertEquals(12, $this->style->getAlign());
        $this->assertInstanceOf(Style::class, $this->style->setAlign(100));
        $this->assertEquals(100, $this->style->getAlign());
    }

    /**
     * @dataProvider alignDataProvider
     */
    public function testAlign($string, $expect): void
    {
        $this->assertEquals($expect, $this->style->align($string, 15));
    }

    public function alignDataProvider(): array
    {
        return [
            [
                'string' => 5,
                'expect' => '           ',
            ],
            [
                'string' => '5',
                'expect' => '               ',
            ],
            [
                'string' => ['123', '456'],
                'expect' => '        ',
            ],
        ];
    }
}
