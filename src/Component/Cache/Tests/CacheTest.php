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

namespace Vection\Component\Cache\Tests;

use Vection\Component\Cache\Cache;
use Vection\Component\Cache\Provider\ArrayCacheProvider;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    public function testMain()
    {
        $provider = new ArrayCacheProvider();

        $cache = new Cache($provider);
        $cache->setString('secret', 'mega');

        $this->assertEquals('mega', $cache->getString('secret'));
    }

}
