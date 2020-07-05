<?php

declare(strict_types=1);

namespace BlueConsole;

use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait AdditionalStyles
{
    /**
     * @var int
     */
    protected $align = 12;

    /**
     * @var int
     */
    protected $timeCharLength = 14;

    /**
     * @var int|float
     */
    protected $time;

    /**
     * @var bool
     */
    protected $showTimer = false;

    /**
     * @var bool
     */
    protected $timerTypeDateTime = false;

    /**
     * @var FormatterHelper
     */
    protected $formatter;

    /**
     * @var string
     */
    protected $dateTimeFormat = 'c';

    /**
     * Style constructor.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param FormatterHelper $formatter
     */
    public function __construct(InputInterface $input, OutputInterface $output, FormatterHelper $formatter)
    {
        $this->formatter = $formatter;

        parent::__construct($input, $output);

        $this->startTime();
    }

    /**
     * @param string $message
     * @param int $length
     * @param string $suffix
     * @return Style
     */
    public function truncate(string $message, int $length, string $suffix = '...'): self
    {
        $this->write($this->truncateMessage($message, $length, $suffix));

        return $this;
    }

    /**
     * @param string $message
     * @param int $length
     * @param string $suffix
     * @return Style
     */
    public function truncateLn(string $message, int $length, string $suffix = '...'): self
    {
        $this->writeln($this->truncateMessage($message, $length, $suffix));

        return $this;
    }

    /**
     * @param string $message
     * @param int $length
     * @param string $suffix
     * @return string
     */
    public function truncateMessage(string $message, int $length, string $suffix = '...'): string
    {
        return $this->formatter->truncate($message, $length, $suffix);
    }

    /**
     * Turn o/off show timer after info block
     */
    public function toggleShowTimer(): void
    {
        $this->showTimer = !$this->showTimer;
    }

    /**
     * Turn o/off show timer after info block
     */
    public function toggleTimerType(): void
    {
        $this->timerTypeDateTime = !$this->timerTypeDateTime;
    }

    /**
     * @return bool
     */
    public function isTimerOn(): bool
    {
        return $this->showTimer;
    }

    /**
     * @param int|null $time
     */
    public function startTime(?int $time = null): void
    {
        if (!$time) {
            $time = \microtime(true);
        }

        $this->time = $time;
    }

    /**
     * @param bool $checkEnableForBlock
     * @return string|null
     * @throws \Exception
     */
    protected function getTimer(bool $checkEnableForBlock = false): ?string
    {
        if ($checkEnableForBlock && !$this->showTimer) {
            return null;
        }

        if ($this->timerTypeDateTime) {
            return $this->getTimerDateTime();
        }

        return $this->getTimerDefault();
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getTimerDateTime(): string
    {
        $dateTime = (new \DateTime())->format($this->dateTimeFormat);

        return "[ <options=bold>$dateTime</> ]";
    }

    /**
     * @return string
     */
    public function getDateTimeFormat(): string
    {
        return $this->dateTimeFormat;
    }

    /**
     * @param string $format
     * @return void
     */
    public function setDateTimeFormat(string $format): void
    {
        $this->dateTimeFormat = $format;
    }

    /**
     * @return string
     */
    protected function getTimerDefault(): string
    {
        $days = null;
        $current = \microtime(true);
        $calc = $current - $this->time;

        if ($calc > self::DAY_SECONDS) {
            $days = \round($calc / self::DAY_SECONDS);
            $calc -= self::DAY_SECONDS * $days;
            $days .= 'd ';

            $this->timeCharLength -= \strlen($days);
        }

        $formatted = \sprintf("%0{$this->timeCharLength}.4f", $calc);

        return "[ <options=bold>$days$formatted</> ]";
    }

    /**
     * @param int $align
     * @return $this
     */
    public function setAlign(int $align): self
    {
        $this->align = $align;

        return $this;
    }

    /**
     * @return int
     */
    public function getAlign(): int
    {
        return $this->align;
    }

    /**
     * @param int $align
     * @return $this
     */
    public function setTimeCharLength(int $align): self
    {
        $this->timeCharLength = $align;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeCharLength(): int
    {
        return $this->timeCharLength;
    }

    /**
     * add some spaces after string depends of it length
     * $string can be numeric/string/array, depends of type it work different
     * if its int inform about string length
     * if its string or object (__toString) it calculate string length for alignment
     * if its array, sum all strings in array length nad add +1for delimiter
     *
     * @param int|string|array|object $string
     * @param int $align
     * @return string
     */
    public function align($string, int $align): string
    {
        $strLength = 0;

        switch (true) {
            case \is_int($string):
                $strLength = $string;
                break;

            case \is_string($string) || \is_object($string):
                $strLength = \mb_strlen((string)$string);
                break;

            case \is_array($string):
                foreach ($string as $message) {
                    $strLength += \mb_strlen($message) + 1;
                }
                break;

            default:
                break;
        }

        $newAlign = ' ';
        $spaces = $align - $strLength;

        for ($i = 1; $i <= $spaces; $i++) {
            $newAlign .= ' ';
        }

        return $newAlign;
    }
}
