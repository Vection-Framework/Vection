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

namespace Vection\Component\DependencyInjection\Tests\Fixtures\AnnotationInjection;

use Vection\Component\DependencyInjection\Attributes\Inject;
use Vection\Component\DependencyInjection\Traits\AnnotationInjection;

/**
 * Class CarEngine
 *
 * @package Vection\Component\DependencyInjection\Tests\Fixtures\AnnotationInjection
 * @author  David Lung <david.lung@appsdock.de>
 */
class Engine
{
    use AnnotationInjection;

    #[Inject] protected Fuel $fuel;

    public function getFuel(): Fuel|null
    {
        return $this->fuel;
    }

}
