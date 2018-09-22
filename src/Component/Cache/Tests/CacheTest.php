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

use PHPUnit\Framework\TestCase;
use Vection\Component\Cache\Cache;
use Vection\Component\Cache\Provider\APCuCacheProvider;
use Vection\Component\Cache\Provider\ArrayCacheProvider;
use Vection\Component\Cache\Provider\RedisCacheProvider;
use Vection\Contracts\Cache\CacheInterface;

/**
 * Class CacheTest
 *
 * @package Vection\Component\Cache\Tests
 */
class CacheTest extends TestCase
{
    public function testArrayCache()
    {
        $provider = new ArrayCacheProvider();
        $cache = new Cache($provider);

        $this->runDefaultCacheTest($cache);
        $this->runPoolCacheTest($cache);
    }

    public function testAPCuCache()
    {
        try{
            $provider = new APCuCacheProvider();
        }catch(\RuntimeException $e){
            // skip test if no apcu extension is installed
            $this->markTestSkipped('Skip test: '.$e->getMessage());
            return;
        }

        $cache = new Cache($provider);
        $this->runDefaultCacheTest($cache);
        $this->runPoolCacheTest($cache);
    }

    public function testRedisCache()
    {
        if( ! extension_loaded('redis') ){
            $this->markTestSkipped('Skip test: RedisCacheProvider requires the redis extension.');
            return;
        }

        $redis = new \Redis();
        $redis->connect('redis://'.getenv('REDIS_HOST'));

        try{
            $this->assertEquals('PONG', $redis->ping());
        }catch(\Exception $e){
            // skip test if no apcu extension is installed
            $this->markTestSkipped('Skip test: '.$e->getMessage());
            return;
        }

        $provider = new RedisCacheProvider($redis);
        $cache = new Cache($provider);
        $this->runDefaultCacheTest($cache);
        $this->runPoolCacheTest($cache);
    }

    public function runDefaultCacheTest(CacheInterface $cache)
    {
        $cache->set('test-default', 'test');
        $this->assertEquals('test', $cache->get('test-default'));
        $cache->delete('test-default');
        $this->assertNull($cache->get('test-default'));

        $cache->setString('test-string', 'foo');
        $this->assertEquals('foo', $cache->getString('test-string'));
        $cache->delete('test-string');
        $this->assertNull($cache->getString('test-string'));

        $cache->setArray('test-array', ['a','b']);
        $this->assertEquals(['a','b'], $cache->getArray('test-array'));
        $cache->delete('test-array');
        $this->assertNull($cache->getArray('test-array'));

        $cache->setFloat('test-float', 1.5);
        $this->assertEquals(1.5, $cache->getFloat('test-float'));
        $cache->delete('test-float');
        $this->assertNull($cache->getFloat('test-float'));

        $cache->setInt('test-int', 2);
        $this->assertEquals(2, $cache->getInt('test-int'));
        $cache->delete('test-int');
        $this->assertNull($cache->getInt('test-int'));

        $object = (object) ['a', 'b'];
        $cache->setObject('test-object', $object);
        $this->assertEquals($object, $cache->getObject('test-object'));
        $cache->delete('test-object');
        $this->assertNull($cache->getObject('test-object'));
    }

    public function runPoolCacheTest(CacheInterface $cache)
    {
        # TODO
    }
}
