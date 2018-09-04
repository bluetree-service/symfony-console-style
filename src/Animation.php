<?php

namespace BlueConsole;

class Animation
{
    /**
     * @var \DateInterval
     */
    protected $interval;

    public function __construct(int $steps = 6, string $cursor = '*')
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

    public function setWaitTime(\DateInterval $dateInterval)
    {
        //        $interval = new \DateInterval('PT10S');
        $dateInterval->invert = true;
        //dump($interval);

        $d1 = new \DateTime();
        while (true) {
            $d2 = new \DateTime();
            $d2->add($dateInterval);

            $iv = $d2->diff($d1);
            dump($iv->s);
            if ($iv->s === 0) {
                echo "end\n";
                exit(0);
            }
            sleep(1);
        }
    }

    public function setMessage(string $message)
    {
        
    }

    public function run($process)
    {
        //display message with animation
        //execute external process
        //wait and display animation
        //process ended -> display message and go fodder
    }

    protected function displayAnimation()
    {
        $counter = 0;
        $forward = true;
        $before = 0;
        $after = $steps - \strlen($cursor);

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

    protected function executeProcess()
    {
        
    }
}
