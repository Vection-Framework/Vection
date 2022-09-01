<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\DependencyInjection\Annotations;

use Vection\Contracts\DependencyInjection\Annotation;

/**
 * Class Inject
 *
 * @Annotation
 *
 * @package Vection\Component\DependencyInjection\Annotations
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
final class Inject implements Annotation
{
    /** @var string|null */
    public ?string $fullQualifiedClassName;

    /**
     * Inject constructor.
     *
     * @param string|null $fullQualifiedClassName
     */
    public function __construct(?string $fullQualifiedClassName = null)
    {
        $this->fullQualifiedClassName = $fullQualifiedClassName;
    }
}
