<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\View;

use Vection\Contracts\View\TemplateInterface;
use Vection\Contracts\View\ViewDataInterface;
use Vection\Contracts\View\ViewInterface;

/**
 * Class View
 *
 * @package Vection\Component\View
 */
class View implements ViewInterface
{
    /**
     * This constant is used to prepend the content
     * to the content array by method prepend(...)
     */
    private const PREPEND = -1;

    /**
     * This constant is used to append the content
     * to the content array by method append(...)
     */
    private const APPEND = 1;

    /**
     * Contains the meta information about the view. This can be
     * each user defined key/value entries. This meta information is not
     * accessible from the template.
     *
     * @var array
     */
    protected $meta = [];

    /**
     * This property contains all the data that has been assigned to
     * this view on runtime. It can also contains the data from the
     * parent view if this view is nested into an other.
     *
     * @var ViewData
     */
    protected $data;

    /**
     * This property contains template independent custom content.
     * It separates prepended and appended content by using the
     * CONTENT_* constants as array key.
     *
     * @var array
     */
    protected $content = [];

    /**
     * This property contains all assigned sub views.
     * The sub views are set by assigning data.
     *
     * @var ViewInterface[]
     */
    protected $views = [];

    /**
     * This is the template the output is based on.
     * The contents of the template will be rendered by
     * translating specific sections and variables with the
     * content of the data property.
     *
     * @var TemplateInterface
     */
    protected $template;


    /**
     * View constructor.
     *
     * @param TemplateInterface $template
     */
    public function __construct(? TemplateInterface $template = null)
    {
        $this->data     = new ViewData();
        $this->content  = [
            self::PREPEND => [],
            self::APPEND  => [],
        ];
        $this->template = $template;
    }

    /**
     * Adds a new entry to the metadata list.
     *
     * @param string $name  The name of the metadata attribute.
     * @param mixed  $value The value for the meta attribute.
     */
    public function addMetadata(string $name, $value): void
    {
        $this->meta[$name] = $value;
    }

    /**
     * Adds a new data entry to the data list.
     *
     * @param string $name  The name which is used by templates content.
     * @param mixed  $value The value of the variable.
     */
    public function assign(string $name, $value): void
    {
        if ( $value instanceof ViewInterface ) {
            $value->getData()->extend($this->data);
            $this->views[$name] = $value;
        } else {
            $this->data->add($name, $value);
        }
    }

    /**
     * Returns an instance of ViewData which contains all
     * assigned data of this view.
     *
     * @return ViewDataInterface
     */
    public function getData(): ViewDataInterface
    {
        return $this->data;
    }

    /**
     * Appends mixed content to the view which is template independent.
     *
     * @param mixed $content
     */
    public function append($content): void
    {
        if ( $content instanceof ViewInterface ) {
            $content->getData()->extend($this->data);
        }

        $this->content[self::APPEND][] = $content;
    }

    /**
     * Prepends mixed content to the view which is template independent.
     *
     * @param mixed $content
     */
    public function prepend($content): void
    {
        if ( $content instanceof ViewInterface ) {
            $content->getData()->extend($this->data);
        }

        $this->content[self::PREPEND][] = $content;
    }

    /**
     * @param string $path
     *
     * @throws \RuntimeException
     */
    public function loadTemplate(string $path): void
    {
        $this->template = new Template($path);
    }

    /**
     * Sets or replaces the template of this view.
     *
     * @param TemplateInterface $template
     */
    public function setTemplate(TemplateInterface $template): void
    {
        $this->template = $template;
    }

    /**
     * Returns an instance of TemplateInterface or null if not set.
     *
     * @return TemplateInterface
     */
    public function getTemplate(): TemplateInterface
    {
        return $this->template;
    }

    /**
     * Renders the content of this view and returns
     * the rendered content considering all sub views
     * and parent data container.
     *
     * @return string
     */
    public function render(): string
    {
        $data = $this->data->toArray();

        foreach ( $this->views as $name => $view ) {
            $data[$name] = $view->render();
        }

        foreach ( $this->content[self::PREPEND] as $key => $content ) {
            if ( $content instanceof ViewInterface ) {
                $this->content[self::PREPEND][$key] = $content->render();
            }
        }

        $renderedContent = \implode("\n", $this->content[self::PREPEND]);

        if ( $this->template ) {
            \extract($data);

            \ob_start();

            // @noinspection PhpIncludeInspection
            include $this->template->getFilePath();

            $renderedContent .= ob_get_clean();
        }

        foreach ( $this->content[self::APPEND] as $key => $content ) {
            if ( $content instanceof ViewInterface ) {
                $this->content[self::APPEND][$key] = $content->render();
            }
        }

        $renderedContent .= \implode("\n", $this->content[self::APPEND]);

        return $renderedContent;
    }
}
