<?php

namespace BlueConsole;

use Symfony\Component\Console\Style\SymfonyStyle;

class Style extends SymfonyStyle
{
    use AdditionalStyles;

    const DAY_SECCONDS = 86400;

    /**
     * @param bool $newLine
     * @return Style
     */
    public function timer(bool $newLine = true): self
    {
        $this->write($this->getTimer());

        if ($newLine) {
            $this->newLine();
        }

        return $this;
    }

    /**
     * @param string $section
     * @param string $message
     * @param string $style
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function formatSection($section, $message, $style = 'info') : self
    {
        $this->writeln(
            $this->formatter->formatSection(
                $section,
                $message,
                $style
            )
        );

        return $this;
    }

    /**
     * @param string|array $messages
     * @param string $style
     * @param bool $large
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function formatBlock($messages, $style, $large = false) : self
    {
        $this->writeln(
            $this->formatter->formatBlock(
                $messages,
                $style,
                $large
            )
        );

        return $this;
    }

    /**
     * @param array $message
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function errorLine(array $message) : self
    {
        return $this->formatBlock($message, 'error');
    }

    /**
     * @param $message
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function okMessage($message) : self
    {
        return $this->renderBlock('  <info>OK</info>  ', $message);
    }

    /**
     * @param $message
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function errorMessage($message) : self
    {
        return $this->renderBlock(' <fg=red>FAIL</> ', $message);
    }

    /**
     * @param string $message
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function warningMessage(string $message) : self
    {
        return $this->renderBlock(' <comment>WARN</comment> ', $message);
    }

    /**
     * @param string $message
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function infoMessage(string $message) : self
    {
        return $this->renderBlock(' <fg=blue>INFO</> ', $message);
    }

    /**
     * @param string $block
     * @param string $message
     * @return Style
     */
    protected function renderBlock(string $block, string $message): self
    {
        $timer = $this->getTimer(true);
        $alignment = $this->align(8, $this->align);

        $this->write("[$block]$timer");
        $this->write($alignment);
        $this->writeln($message);

        return $this;
    }

    public function message($message, $color, $label) : self
    {
        if ($color) {

        }

        if ($label) {

        }

        $this->writeln($message);

        return $this;
    }

    /**
     * @param string $message
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function note($message) : self
    {
        return $this->genericBlock($message, 'blue', 'note');
    }

    /**
     * @param string $message
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function caution($message) : self
    {
        return $this->genericBlock($message, 'magenta', 'caution');
    }

    /**
     * @param string $message
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function success($message) : self
    {
        return $this->genericBlock($message, 'green', 'success');
    }

    /**
     * @param string $message
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function warning($message) : self
    {
        return $this->genericBlock($message, 'yellow', 'warning');
    }

    /**
     * @param string $message
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function error($message) : self
    {
        return $this->genericBlock($message, 'red', 'error');
    }

    /**
     * @param string $message
     * @param string $background
     * @param string $type
     * @param int $length
     * @return $this
     * @throws \InvalidArgumentException
     * @todo if type ==='' don't display []
     */
    public function genericBlock($message, $background, $type, $length = 100) : self
    {
        $type = strtoupper($type);
        $alignment = $this->align(0, $length);
        $alignmentMessage = $this->align($message, $length - (mb_strlen($type) + 5));

        $this->writeln("<bg=$background>$alignment</>");
        $this->writeln("<fg=white;bg=$background>  [$type] $message$alignmentMessage</>");
        $this->writeln("<bg=$background>$alignment</>");
        $this->newLine();

        return $this;
    }

    /**
     * @todo add multi line block
     * @todo add php 7.1 features
     * @todo
     * [ ***  ]
     */

    /*
        http://symfony.com/doc/current/components/console/helpers/formatterhelper.html
        https://symfony.com/doc/current/console/style.html
    */
}
