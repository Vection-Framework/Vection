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

namespace Vection\Contracts\Cache;

/**
 * Interface CacheAwareInterface
 *
 * @package Vection\Contracts\Cache
 */
interface CacheAwareInterface
{
    /**
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache): void;
}
