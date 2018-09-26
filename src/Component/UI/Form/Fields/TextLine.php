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

namespace Vection\Component\UI\Form\Fields;

use Vection\Component\UI\Form\Field;

/**
 * Class TextLine
 *
 * @package Vection\Component\UI\Form\Fields
 */
class TextLine extends Field
{
    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->data['type'] = $type;
    }

    /**
     * @param string $icon
     * @param string $position
     */
    public function setIcon(string $icon, string $position = 'left'): void
    {
        $this->data['icon'] = $icon;
        $this->data['iconPosition'] = $position;
    }

    /**
     * Initialize the field specific definition.
     */
    protected function onDefine(): array
    {
        return [
            'type'         => 'text',
            'icon'         => null,
            'iconPosition' => null,
            'placeholder'  => '',
        ];
    }
}