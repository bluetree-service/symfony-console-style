<?php
/**
 * Created by PhpStorm.
 * User: chajr
 * Date: 2019-05-04
 * Time: 10:15
 */

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
     * @var int
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
    public function truncate(string $message, int $length, string $suffix = '...') : self
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
    public function truncateLn(string $message, int $length, string $suffix = '...') : self
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
    public function truncateMessage(string $message, int $length, string $suffix = '...') : string
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
    protected function getTimer(bool $checkEnableForBlock = false):? string
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
        return (new \DateTime)->format('%c');
    }

    /**
     * @return string
     */
    protected function getTimerDefault(): string
    {
        $days = null;
        $current = \microtime(true);
        $calc = $current - $this->time;

        if ($calc > self::DAY_SECCONDS) {
            $days = \round($calc / self::DAY_SECCONDS);
            $calc -= self::DAY_SECCONDS * $days;
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
    public function setAlign($align) : self
    {
        $this->align = $align;

        return $this;
    }

    /**
     * @return int
     */
    public function getAlign() : int
    {
        return $this->align;
    }

    /**
     * @param int $align
     * @return $this
     */
    public function setTimeCharLength($align) : self
    {
        $this->timeCharLength = $align;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeCharLength() : int
    {
        return $this->timeCharLength;
    }

    /**
     * @param string|int $strLength
     * @param int $align
     * @return string
     */
    public function align($strLength, $align) : string
    {
        if (\is_string($strLength)) {
            $strLength = mb_strlen($strLength);
        }

        $newAlign = ' ';
        $spaces = $align - $strLength;

        for ($i = 1; $i <= $spaces; $i++) {
            $newAlign .= ' ';
        }

        return $newAlign;
    }
}
