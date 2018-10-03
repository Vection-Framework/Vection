<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\DI\Annotations;

/**
 * Class Inject
 *
 * @Annotation
 *
 * @package Vection\Component\DI\Annotations
 */
final class Inject
{
    /** @var string */
    public $class;

    /** @var string */
    public $property;

    /**
     * Inject constructor.
     *
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }
}