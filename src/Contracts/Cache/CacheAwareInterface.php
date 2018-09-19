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

namespace Vection\Contracts\Cache;

/**
 * Interface CacheAwareInterface
 *
 * @package Vection\Contracts\Cache
 */
interface CacheAwareInterface
{
    public function setCache(CacheInterface $cache): void;

    public function getCache(): CacheInterface;
}