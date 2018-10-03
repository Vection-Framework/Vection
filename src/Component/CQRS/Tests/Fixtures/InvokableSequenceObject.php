<?php


/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\CQRS\Tests\Fixtures;

/**
 * Class InvokableSequenceObject
 *
 * @package Vection\Component\CQRS\Tests\Fixtures
 */
class InvokableSequenceObject
{
    /** @var bool */
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