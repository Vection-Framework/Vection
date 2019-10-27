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

use Vection\Component\Event\Annotation\EventName;
use Vection\Component\Event\Event;

/**
 * Class AnnotatedTestEvent
 *
 * @package Vection\Component\Event\Tests\Fixtures
 *
 * @EventName("vection.annotationTested")
 */
final class AnnotatedTestEvent extends Event
{

    /** @var string */
    private $secret;

    /**
     * AnnotatedTestEvent constructor.
     *
     * @param string $secret
     */
    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }
}
