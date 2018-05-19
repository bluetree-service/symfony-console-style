<?php
/**
 * Created by PhpStorm.
 * User: chajr
 * Date: 15.05.18
 * Time: 20:33
 */

namespace BluetreeConsole;


class MultiSelect
{
    public function __construct()
    {
        $list = [
            'option 1',
            'option 2',
            'option 3',
            'option 4',
            'option 5',
        ];
        $availableChars = [10, 32, 65, 66];
        $selectedOptions = [];


        system('stty cbreak -echo');
        $stdin = fopen('php://stdin', 'r');

        $cursor = 0;

//echo "empty \n";
        foreach ($list as $key => $row) {
            $cursorChar = ' ';
            if ($cursor === $key) {
                $cursorChar = '❯';
            }
            echo " $cursorChar [ ] $row\n";
        }


        while (1) {
            $listSize = count($list);

            $c = ord(fgetc($stdin));
            if (!in_array($c, $availableChars)) {
                continue;
            }

            if ($c === 10) {
                echo "\nSelected:\n";
                foreach ($list as $key => $row) {
                    if (array_key_exists($key, $selectedOptions)) {
                        echo "$key: $row\n";
                    }
                }
                break;
                exit(0);
            }

            for ($i = 0; $i < $listSize; $i++) {
                echo "\033[1A";
            }
//        echo "\033[1A";

            if ($c === 65 && $cursor > 0) {
                $cursor--;
            }

            if ($c === 66 && $cursor < $listSize -1) {
                $cursor++;
            }

            if ($c === 32) {
                if (isset($selectedOptions[$cursor])) {
                    unset($selectedOptions[$cursor]);
                } else {
                    $selectedOptions[$cursor] = true;
                }
            }

//    echo implode(',',array_keys($selectedOptions));
//    echo"\n";

            foreach ($list as $key => $row) {
                $cursorChar = ' ';
                $selected = '[ ]';
                if ($cursor === $key) {
                    $cursorChar = '❯';
                }
                if (array_key_exists($key, $selectedOptions)) {
                    $selected = '[✓]';
                }
                echo " $cursorChar $selected $row\n";
            }
            sleep(.5);
        }

//count line length and select highest value to replace all chars
//option scroll
//separator (---- by default, optionally message)
    }
}