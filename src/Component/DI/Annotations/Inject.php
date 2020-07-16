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

namespace Vection\Component\DI\Annotations;

use Vection\Contracts\DI\Annotation;

/**
 * Class Inject
 *
 * @Annotation
 *
 * @package Vection\Component\DI\Annotations
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
final class Inject implements Annotation
{
    /** @var string|null */
    public $fullQualifiedClassName;

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
