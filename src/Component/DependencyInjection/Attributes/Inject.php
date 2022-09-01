<?php

/*
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\DependencyInjection\Attributes;

use Attribute;

/**
 * Attribute Inject
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 *
 * @package Vection\Component\DependencyInjection\Attributes
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
class Inject
{
    /**
     * Inject constructor.
     */
    public function __construct()
    {}

}
