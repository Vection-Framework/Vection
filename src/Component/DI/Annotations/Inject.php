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

/**
 * Class Inject
 *
 * @Annotation
 *
 * @package Vection\Component\DI\Annotations
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
final class Inject
{

    /** @var string|null */
    public $fqcn;

    /**
     * Inject constructor.
     *
     * @param string $fqcn
     */
    public function __construct(?string $fqcn = null)
    {
        $this->fqcn = $fqcn;
    }
}
