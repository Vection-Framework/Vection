<?php


/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) Vection <project@vection.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\DI\Tests\Fixtures;

/**
 * Class TestObject
 *
 * @package Vection\Component\DI\Tests\Fixtures
 */
class TestObject
{
    private $byDef;

    /**
     * TestObject constructor.
     *
     * @param bool $byDef
     */
    public function __construct(bool $byDef = false)
    {
        $this->byDef = $byDef;
    }

    /**
     * @return bool
     */
    public function instantiatedByDef(): bool
    {
        return $this->byDef;
    }
}