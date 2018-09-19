<?php
/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) David Lung <aleqz.lung@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\UI;

/**
 * Class UIComponent
 *
 * @package Vection\Component\UI
 */
abstract class UIComponent
{
    /** @var string */
    protected $templateDirectory;

    /**
     * UIComponent constructor.
     *
     * @param string $templateDirectory
     */
    public function __construct(string $templateDirectory)
    {
        $this->templateDirectory = $templateDirectory;
    }

    /**
     *
     * @return string
     */
    public function render(): string
    {
        extract($this->toArray());

        ob_start() AND include( $this->templateDirectory . 'main.phtml' );

        return ob_get_clean();
    }

    /**
     * @return array
     */
    abstract protected function toArray(): array;
}