<?php

namespace BlueConsole;

class Animation
{
    public function __construct($steps = 6, $cursor = '*')
    {
        $counter = 0;
        $forward = true;
        $before = 0;
        $after = $steps - \strlen($cursor);

        echo "\n";

        while (true) {
            echo '[';

            for ($i = 1; $i <= $before; $i++) {
                echo ' ';
            }

            echo $cursor;

            for ($i = 0; $i <= $after; $i++) {
                echo ' ';
            }

            echo ']';
            echo "\r";

            $counter++;

            if ($forward) {
                $before++;
                $after--;
            } else {
                $before--;
                $after++;
            }

            if ($counter === ($steps - (\strlen($cursor) -1))) {
                $counter = 0;
                $forward = !$forward;
            }

            sleep(1);
        }
    }
}
