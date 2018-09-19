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

namespace Vection\Component\UI\Form;

/**
 * Class Control
 *
 * @package Vection\Component\UI\Form
 */
class Control
{
    /** @var array */
    protected $data;

    /**
     * Control constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->data = [
            'type'  => $type,
            'title' => null,
            'icon'  => null,
        ];
    }

    /**
     * @param array $definition
     */
    public function setDefinition(array $definition): void
    {
        $this->data = array_merge($this->data, $definition);
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->data['title'] = $title;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon): void
    {
        $this->data['icon'] = $icon;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}