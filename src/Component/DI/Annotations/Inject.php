<?php declare(strict_types=1);

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
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
    public $fqcn;

    /**
     * Inject constructor.
     *
     * @param string $fqcn
     */
    public function __construct(string $fqcn = '')
    {
        $this->fqcn = $fqcn;
    }
}