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

namespace Vection\Component\Event\Annotation;

/**
 * Class Subscribe
 *
 * @Annotation Subscribe
 *
 * @package    Vection\Component\Event\Annotation
 */
final class Subscribe
{
    /** @var string */
    public $event;

    /** @var string */
    public $method;

    /** @var int */
    public $priority = 0;

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return (int)$this->priority;
    }
}