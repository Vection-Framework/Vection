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

namespace Vection\Component\MessageBus\Tests\Fixtures;

/**
 * Class InvokableSequenceObject
 *
 * @package Vection\Component\MessageBus\Tests\Fixtures
 */
class InvokableSequenceObject
{

    /** @var boolean */
    private $isInvoked = false;

    /**
     * @return bool
     */
    public function isInvoked(): bool
    {
        return $this->isInvoked;
    }

    public function __invoke()
    {
        $this->isInvoked = true;
    }
}
