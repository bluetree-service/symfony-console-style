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

    public function testFormatBlockArray(): void
    {
        $expect = <<<EOT
<ok>      </ok>
<ok>  m1  </ok>
<ok>      </ok>
<ok>      </ok>
<ok>  m2  </ok>
<ok>      </ok>

EOT;

        $this->style->formatBlock(['m1', 'm2'], 'ok', true);
        $this->assertEquals($expect, $this->getDisplay());
    }

    public function testFormatBlock(): void
    {
        $expect = <<<EOT
<ok>      </ok>
<ok>  m1  </ok>
<ok>      </ok>

EOT;

        $this->style->formatBlock('m1', 'ok', true);
        $this->assertEquals($expect, $this->getDisplay());
    }

    public function testErrorLine(): void
    {
        $this->style->errorLine(['m1']);
        $this->assertEquals(" m1 \n", $this->getDisplay());
    }

    public function testGenericBlock(): void
    {
        $expected = <<<EOT
                                                   
  [ERROR] m1                                       
                                                   


EOT;

        $this->style->genericBlock('m1', 'red', 'error', 50);
        $this->assertEquals($expected, $this->getDisplay());
    }

    public function testGenericBlockWithMissingType(): void
    {
        $this->style->genericBlock('m1', 'red', '');
        $this->assertEquals('', $this->getDisplay());
    }

    public function testNote(): void
    {
        $expected = <<<EOT
                                                                                                     
  [NOTE] m1                                                                                          
                                                                                                     


EOT;

        $this->style->note('m1');
        $this->assertEquals($expected, $this->getDisplay());
    }

    public function testCaution(): void
    {
        $expected = <<<EOT
                                                                                                     
  [CAUTION] m1                                                                                       
                                                                                                     


EOT;

        $this->style->caution('m1');
        $this->assertEquals($expected, $this->getDisplay());
    }

    public function testSuccess(): void
    {
        $expected = <<<EOT
                                                                                                     
  [SUCCESS] m1                                                                                       
                                                                                                     


EOT;

        $this->style->success('m1');
        $this->assertEquals($expected, $this->getDisplay());
    }

    public function testWarning(): void
    {
        $expected = <<<EOT
                                                                                                     
  [WARNING] m1                                                                                       
                                                                                                     


EOT;

        $this->style->warning('m1');
        $this->assertEquals($expected, $this->getDisplay());
    }

    public function testError(): void
    {
        $expected = <<<EOT
                                                                                                     
  [ERROR] m1                                                                                         
                                                                                                     


EOT;

        $this->style->error('m1');
        $this->assertEquals($expected, $this->getDisplay());
    }
}
