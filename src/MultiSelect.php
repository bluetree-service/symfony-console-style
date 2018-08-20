<?php
/**
 * Created by PhpStorm.
 * User: chajr
 * Date: 15.05.18
 * Time: 20:33
 */

namespace BlueConsole;

use Symfony\Component\Console\Style\SymfonyStyle;

class MultiSelect
{
    /**
     * @todo add help bellow (move up, down, space enter)
     * @todo add separators
     * @todo count line length and select highest value to replace all chars
     * @todo option scroll
     * @todo separator (---- by default, optionally message)
     * @todo comments on array list
     */
    protected const CHARS = [
        'enter' => 10,
        'space' => 32,
        'key_up' => 65,
        'key_down' => 66
    ];

    protected const MOD_LINE_CHAR = "\033[1A";

    /**
     * @var string
     */
    protected $selectChar = '<fg=blue>❯</>';

    /**
     * @var string
     */
    protected $selectedChar = '<info>✓</info>';

    /**
     * @var SymfonyStyle $output
     */
    protected $output;

    /**
     * @var bool
     */
    protected $showInfo = true;

    /**
     * @var bool|resource
     */
    protected $stdin;

    /**
     * @param $output
     */
    public function __construct(SymfonyStyle $output)
    {
        $this->output = $output;
        $this->stdin = fopen('php://stdin', 'rb');
        system('stty cbreak -echo');
    }

    /**
     * @param bool $showInfo
     * @return $this
     */
    public function toggleShowInfo(bool $showInfo) : self
    {
        $this->showInfo = $showInfo;

        return $this;
    }

    /**
     * @param string $char
     * @return $this
     */
    public function setSelectChar(string $char) : self
    {
        $this->selectChar = $char;

        return $this;
    }

    /**
     * @param string $char
     * @return $this
     */
    public function setSelectedChar(string $char) : self
    {
        $this->selectedChar = $char;

        return $this;
    }

    /**
     * @param array $dataList
     * @return array
     */
    public function renderMultiSelect(array $dataList) : array
    {
        $selectedOptions = [];
        $cursor = 0;
        $listSize = \count($dataList);

        $this->renderBasicList($dataList);

        while (true) {
            $char = \ord(fgetc($this->stdin));

            if (!\in_array($char, self::CHARS, true)) {
                continue;
            }

            if ($char === self::CHARS['enter']) {
                $this->renderSelectionInfo($dataList, $selectedOptions);

                break;
            }

            for ($i = 0; $i < $listSize; $i++) {
                echo self::MOD_LINE_CHAR;
            }

            [$cursor, $selectedOptions] = $this->manageCursor($cursor, $char, $listSize, $selectedOptions);

            $this->renderListWithSelection($dataList, $cursor, $selectedOptions);

            sleep(.5);
        }

        return $selectedOptions;
    }

    /**
     * @param array $dataList
     * @return null|int
     */
    public function renderSingleSelect(array $dataList) :? int
    {
        $selectedOptions = [];
        $cursor = 0;
        $listSize = \count($dataList);

        $this->renderBasicList($dataList);

        while (true) {
            $char = \ord(fgetc($this->stdin));

            if (!\in_array($char, self::CHARS, true)) {
                continue;
            }

            if ($char === self::CHARS['enter']) {
                $this->renderSelectionInfo($dataList, $selectedOptions);

                break;
            }

            for ($i = 0; $i < $listSize; $i++) {
                echo self::MOD_LINE_CHAR;
            }

            [$cursor, $selectedOptions] = $this->manageCursor($cursor, $char, $listSize, $selectedOptions, true);

            $this->renderListWithSelection($dataList, $cursor, $selectedOptions);

            sleep(.5);
        }

        $keys = array_keys($selectedOptions);
        return reset($keys);
    }

    /**
     * @param int $cursor
     * @param int $char
     * @param int $listSize
     * @param bool $isSingleSelect
     * @param array $selectedOptions
     * @return array
     */
    protected function manageCursor(
        int $cursor,
        int $char,
        int $listSize,
        array $selectedOptions,
        bool $isSingleSelect = false
    ) : array {
        if ($cursor > 0 && $char === self::CHARS['key_up']) {
            $cursor--;
        }

        if ($cursor < $listSize -1 && $char === self::CHARS['key_down']) {
            $cursor++;
        }

        if ($char === self::CHARS['space']) {
            [$selectedOptions, $oldSelections] = $this->singleSelection($isSingleSelect, $cursor, $selectedOptions);

            if ($oldSelections || isset($selectedOptions[$cursor])) {
                unset($selectedOptions[$cursor]);
            } else {
                $selectedOptions[$cursor] = true;
            }
        }

        return [$cursor, $selectedOptions];
    }

    /**
     * @param bool $isSingleSelect
     * @param int $cursor
     * @param array $selectedOptions
     * @return array
     */
    protected function singleSelection(bool $isSingleSelect, int $cursor, array $selectedOptions) : array
    {
        $oldSelections = false;

        if ($isSingleSelect) {
            if (isset($selectedOptions[$cursor])) {
                $oldSelections = true;
            }

            $selectedOptions = [];
        }

        return [$selectedOptions, $oldSelections];
    }

    /**
     * @param array $dataList
     * @param array $selectedOptions
     * @return MultiSelect
     */
    protected function renderSelectionInfo(array $dataList, $selectedOptions) : self
    {
        if (!$this->showInfo) {
            return $this;
        }

        $this->output->writeln('');
        $this->output->title('Selected:');

        echo self::MOD_LINE_CHAR;

        foreach ($dataList as $key => $row) {
            if (array_key_exists($key, $selectedOptions)) {
                $this->output->writeln("$key: <info>$row</info>");
            }
        }

        return $this;
    }

    /**
     * @param array $dataList
     * @param $cursor
     * @param $selectedOptions
     * @return MultiSelect
     */
    protected function renderListWithSelection(array $dataList, $cursor, $selectedOptions) : self
    {
        foreach ($dataList as $key => $row) {
            $cursorChar = ' ';
            $selected = '[ ]';

            if ($cursor === $key) {
                $cursorChar = $this->selectChar;
            }

            if ($cursorChar !== ' ') {
                $selected = "<fg=blue>$selected</>";
            }

            if (array_key_exists($key, $selectedOptions)) {
                $selected = '[' . $this->selectedChar . ']';
            }

            // resolve colors
            if ($cursorChar !== ' ') {
                $row = "<fg=blue>$row</>";
            } else {
                $row = "<comment>$row</comment>";
            }

            $this->output->writeln(" $cursorChar $selected $row");
        }

        return $this;
    }

    /**
     * @param array $dataList
     * @return MultiSelect
     */
    protected function renderBasicList(array $dataList) : self
    {
        $count = 0;

        foreach ($dataList as $key => $row) {
            $cursorChar = ' ';

            if ($key === 0) {
                $cursorChar = $this->selectChar;
            }

            if ($count++ === 0) {
                 $this->output->writeln(" $cursorChar <fg=blue>[ ]</> <comment>$row</comment>");
            } else {
                $this->output->writeln(" $cursorChar [ ] <comment>$row</comment>");
            }
        }

        return $this;
    }
}
