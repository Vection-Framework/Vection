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

namespace Vection\Component\DI\Traits;

/**
 * Trait AnnotationInjection
 *
 * @package Vection\Component\DI\Traits
 */
trait AnnotationInjection
{
    /**
     * Injects dependency objects into object properties.
     *
     * @param array $args
     */
    public function __annotationInjection(array $args)
    {
        foreach ( $args as $prop => $dep ) {
            $this->$prop = $dep;
        }
    }
}