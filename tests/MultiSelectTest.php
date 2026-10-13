<?php

namespace Test;

use BlueConsole\MultiSelect;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

class MultiSelectTest extends TestCase
{
    public function testPreselectedLockedAndScroll(): void
    {
        $stdin = \fopen('php://memory', 'wb+');
        // space on locked row 0 (ignored), down, down, space (select 2), down x2 (scroll), enter
        \fwrite($stdin, " BB BB\n");
        \rewind($stdin);
        $output = new BufferedOutput();

        $selected = (new MultiSelect(new SymfonyStyle(new ArgvInput([]), $output), $stdin))
            ->toggleShowInfo(false)
            ->setPageSize(2)
            ->renderMultiSelect(['a', 'b', 'c', 'd', 'e'], [1], [0]);

        \ksort($selected);
        $this->assertSame([0, 1, 2], \array_keys($selected));
        $display = $output->fetch();
        $this->assertStringContainsString('1-2 / 5', $display);
        $this->assertStringContainsString('4-5 / 5', $display);
        $this->assertStringContainsString('[●] a', $display);
    }

    public function testUnselectPreselected(): void
    {
        $stdin = \fopen('php://memory', 'wb+');
        \fwrite($stdin, " \n");
        \rewind($stdin);

        $selected = (new MultiSelect(new SymfonyStyle(new ArgvInput([]), new BufferedOutput()), $stdin))
            ->toggleShowInfo(false)
            ->renderMultiSelect(['a', 'b'], [0, 1]);

        $this->assertSame([1], \array_keys($selected));
    }

    public function testCursorOnConfirm(): void
    {
        $stdin = \fopen('php://memory', 'wb+');
        \fwrite($stdin, "BB\n");
        \rewind($stdin);
        $select = (new MultiSelect(new SymfonyStyle(new ArgvInput([]), new BufferedOutput()), $stdin))
            ->toggleShowInfo(false);

        $select->renderMultiSelect(['a', 'b', 'c']);

        $this->assertSame(2, $select->getCursor());
    }
}
