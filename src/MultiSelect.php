<?php

declare(strict_types=1);

namespace BlueConsole;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Terminal;

class MultiSelect
{
    /**
     * @todo add help bellow (move up, down, space enter)
     * @todo add separators
     * @todo count line length and select highest value to replace all chars
     * @todo separator (---- by default, optionally message)
     * @todo comments on array list
     * @todo use different select char & brackets for single select
     */
    protected const CHARS = [
        'enter' => 10,
        'space' => 32,
        'key_up' => 65,
        'key_down' => 66
    ];

    public const MOD_LINE_CHAR = "\033[1A";

    /**
     * @var string
     */
    protected string $selectChar = '<fg=blue>❯</>';

    /**
     * @var string
     */
    protected string $selectedChar = '<info>✓</info>';

    /**
     * @var string
     */
    protected string $lockedChar = '<fg=gray>●</>';

    /**
     * @var null|int null = terminal height
     */
    protected ?int $pageSize = null;

    /**
     * @var int first visible row
     */
    protected int $offset = 0;

    /**
     * @var int cursor position when list was confirmed
     */
    protected int $cursor = 0;

    /**
     * @var SymfonyStyle $output
     */
    protected SymfonyStyle $output;

    /**
     * @var bool
     */
    protected bool $showInfo = true;

    /**
     * @var bool|resource
     */
    protected $stdin;

    /**
     * @param SymfonyStyle $output
     * @param null|resource $stdin custom input stream (tests), terminal mode is not changed then
     */
    public function __construct(SymfonyStyle $output, $stdin = null)
    {
        $this->output = $output;
        $this->stdin = $stdin;

        if ($stdin === null) {
            $this->stdin = \fopen('php://stdin', 'rb');
            system('stty cbreak -echo');
        }
    }

    /**
     * @param bool $showInfo
     * @return $this
     */
    public function toggleShowInfo(bool $showInfo): self
    {
        $this->showInfo = $showInfo;

        return $this;
    }

    /**
     * @param string $char
     * @return $this
     */
    public function setSelectChar(string $char): self
    {
        $this->selectChar = $char;

        return $this;
    }

    /**
     * @param string $char
     * @return $this
     */
    public function setSelectedChar(string $char): self
    {
        $this->selectedChar = $char;

        return $this;
    }

    /**
     * @return int row under cursor when last list was confirmed (enter)
     */
    public function getCursor(): int
    {
        return $this->cursor;
    }

    /**
     * @param string $char
     * @return $this
     */
    public function setLockedChar(string $char): self
    {
        $this->lockedChar = $char;

        return $this;
    }

    /**
     * @param null|int $pageSize visible rows, longer list scrolls; null = terminal height
     * @return $this
     */
    public function setPageSize(?int $pageSize): self
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * @param array $dataList
     * @param int[] $selected keys selected at start
     * @param int[] $locked keys selected at start which can't be unselected
     * @return array selected keys (with locked) as [key => true]
     */
    public function renderMultiSelect(array $dataList, array $selected = [], array $locked = []): array
    {
        return $this->renderList($dataList, false, $selected, $locked);
    }

    /**
     * @param array $dataList
     * @return null|int
     */
    public function renderSingleSelect(array $dataList): ?int
    {
        $selectedOptions = $this->renderList($dataList, true);

        $keys = \array_keys($selectedOptions);
        return \reset($keys);
    }

    /**
     * @param array $dataList
     * @param bool $isSingleSelect
     * @param int[] $selected
     * @param int[] $locked
     * @return array
     */
    protected function renderList(
        array $dataList,
        bool $isSingleSelect,
        array $selected = [],
        array $locked = []
    ): array {
        $locked = \array_fill_keys($locked, true);
        $selectedOptions = \array_fill_keys($selected, true) + $locked;
        $cursor = 0;
        $listSize = \count($dataList);
        $this->offset = 0;

        $lines = $this->renderListWithSelection($dataList, $cursor, $selectedOptions, $locked);

        while (true) {
            $read = \fgetc($this->stdin);

            if ($read === false) {
                break;
            }

            $char = \ord($read);

            if (!\in_array($char, self::CHARS, true)) {
                continue;
            }

            if ($char === self::CHARS['enter']) {
                $this->renderSelectionInfo($dataList, $selectedOptions);

                break;
            }

            echo \str_repeat(self::MOD_LINE_CHAR, $lines);

            [$cursor, $selectedOptions] = $this->manageCursor(
                $cursor,
                $char,
                $listSize,
                $selectedOptions,
                $isSingleSelect,
                $locked
            );

            $lines = $this->renderListWithSelection($dataList, $cursor, $selectedOptions, $locked);

            \usleep(100000);
        }

        $this->cursor = $cursor;

        return $selectedOptions;
    }

    /**
     * @param int $cursor
     * @param int $char
     * @param int $listSize
     * @param bool $isSingleSelect
     * @param array $selectedOptions
     * @param array $locked [key => true]
     * @return array
     */
    protected function manageCursor(
        int $cursor,
        int $char,
        int $listSize,
        array $selectedOptions,
        bool $isSingleSelect = false,
        array $locked = []
    ): array {
        if ($cursor > 0 && $char === self::CHARS['key_up']) {
            $cursor--;
        }

        if ($cursor < $listSize - 1 && $char === self::CHARS['key_down']) {
            $cursor++;
        }

        if ($char === self::CHARS['space'] && !isset($locked[$cursor])) {
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
    protected function singleSelection(bool $isSingleSelect, int $cursor, array $selectedOptions): array
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
    protected function renderSelectionInfo(array $dataList, array $selectedOptions): self
    {
        if (!$this->showInfo) {
            return $this;
        }

        $this->output->writeln('');
        $this->output->title('Selected:');

        echo self::MOD_LINE_CHAR;

        foreach ($dataList as $key => $row) {
            if (\array_key_exists($key, $selectedOptions)) {
                $this->output->writeln("$key: <info>$row</info>");
            }
        }

        return $this;
    }

    /**
     * @param array $dataList
     * @param int $cursor
     * @param array $selectedOptions
     * @param array $locked [key => true]
     * @return int rendered lines count
     */
    protected function renderListWithSelection(
        array $dataList,
        int $cursor,
        array $selectedOptions,
        array $locked = []
    ): int {
        $listSize = \count($dataList);
        $height = \min($listSize, $this->pageSize ?? \max(5, (new Terminal())->getHeight() - 4));

        if ($cursor < $this->offset) {
            $this->offset = $cursor;
        }

        if ($cursor >= $this->offset + $height) {
            $this->offset = $cursor - $height + 1;
        }

        foreach (\array_slice($dataList, $this->offset, $height, true) as $key => $row) {
            $isCursor = $cursor === $key;
            $cursorChar = $isCursor ? $this->selectChar : ' ';
            $selected = $isCursor ? '<fg=blue>[ ]</>' : '[ ]';

            if (isset($locked[$key])) {
                $selected = '[' . $this->lockedChar . ']';
            } elseif (\array_key_exists($key, $selectedOptions)) {
                $selected = '[' . $this->selectedChar . ']';
            }

            //@todo resolve colors
            $row = $isCursor ? "<fg=blue>$row</>" : "<comment>$row</comment>";

            $this->output->writeln(" $cursorChar $selected $row");
        }

        if ($height === $listSize) {
            return $height;
        }

        $this->output->writeln(
            \sprintf(' <fg=gray>%d-%d / %d</>', $this->offset + 1, $this->offset + $height, $listSize)
        );

        return $height + 1;
    }
}
