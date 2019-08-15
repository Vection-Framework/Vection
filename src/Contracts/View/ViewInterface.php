<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Contracts\View;

/**
 * Interface ViewInterface
 *
 * @package Vection\Contracts\View
 */
interface ViewInterface
{
    /**
     * Adds a new entry to the metadata list.
     *
     * @param string $name  The name of the metadata attribute.
     * @param mixed  $value The value for the meta attribute.
     */
    public function addMetadata(string $name, $value): void;

    /**
     * Adds a new data entry to the data list.
     *
     * @param string $name The name which is used by templates content.
     * @param mixed $value The value of the variable.
     */
    public function assign(string $name, $value): void;

    /**
     * Returns an instance of DataContainer which contains all
     * assigned data of this view.
     *
     * @return ViewDataInterface
     */
    public function getData(): ViewDataInterface;

    /**
     * Appends mixed content to the view which is template independent.
     *
     * @param $content
     */
    public function append($content): void;

    /**
     * Prepends mixed content to the view which is template independent.
     *
     * @param $content
     */
    public function prepend($content): void;

    /**
     * Sets or replaces the template of this view.
     *
     * @param TemplateInterface $template
     */
    public function setTemplate(TemplateInterface $template): void;

    /**
     * Returns an instance of TemplateInterface or null if not set.
     *
     * @return TemplateInterface
     */
    public function getTemplate(): TemplateInterface;

    /**
     * Renders the content of this view and returns
     * the rendered content considering all sub views
     * and parent data container.
     *
     * @return string
     */
    public function render(): string;
}
