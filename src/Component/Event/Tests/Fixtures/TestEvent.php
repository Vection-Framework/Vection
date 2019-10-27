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

namespace Vection\Component\Event\Tests\Fixtures;

use Vection\Component\Event\Event;

/**
 * Class TestEvent
 *
 * @package Vection\Component\Event\Tests\Fixtures
 */
final class TestEvent extends Event
{
    const NAME = 'vection.unit.test';

    /** @var string */
    private $string;

    /**
     * TestEvent constructor.
     *
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->string = $string;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }

}
