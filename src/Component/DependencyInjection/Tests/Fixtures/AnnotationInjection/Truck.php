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

/**
 * Class Truck
 *
 * @package Vection\Component\DependencyInjection\Tests\Fixtures\AnnotationInjection
 * @author  David Lung <david.lung@appsdock.de>
 */
class Truck extends Vehicle
{
    #[Inject] protected Trailer $trailer;

    public function getTrailer(): Trailer|null
    {
        return $this->trailer;
    }
}
