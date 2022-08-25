<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Cache\Tests;

use PHPUnit\Framework\TestCase;
use stdClass;
use Vection\Component\Cache\Provider\ArrayCacheProvider;
use Vection\Contracts\Cache\CacheProviderInterface;

/**
 * Class ArrayCacheProviderTest
 *
 * @package Vection\Component\Cache\Tests
 */
class ArrayCacheProviderTest extends TestCase
{
    /**
     * @return array
     */
    public function getCacheProvider(): array
    {
        return [
            [new ArrayCacheProvider()]
        ];
    }

    /**
     * @dataProvider getCacheProvider
     *
     * @param CacheProviderInterface $cache
     */
    public function testDelete(CacheProviderInterface $cache): void
    {
        $cache->set('testKey', 'vection');
        $cache->delete('testKey');

        $this->assertFalse($cache->contains('testKey'));
        $this->assertNull($cache->get('testKey'));
    }

    /**
     * @dataProvider getCacheProvider
     *
     * @param CacheProviderInterface $cache
     */
    public function testGetString(CacheProviderInterface $cache): void
    {
        $cache->setString('testKey', 'vection');
        $value = $cache->getString('testKey');

        $this->assertNotNull($value);
        $this->assertTrue(is_string($value), 'Unexpected type '.gettype($value));
        $this->assertEquals($value,'vection');
    }

    /**
     * @dataProvider getCacheProvider
     *
     * @param CacheProviderInterface $cache
     */
    public function testGetFloat(CacheProviderInterface $cache): void
    {
        $cache->setFloat('testKey', 1.5);
        $value = $cache->getFloat('testKey');

        $this->assertNotNull($value);
        $this->assertTrue(is_float($value), 'Unexpected type '.gettype($value));
        $this->assertEquals($value,1.5);
    }

    /**
     * @dataProvider getCacheProvider
     *
     * @param CacheProviderInterface $cache
     */
    public function testGetArray(CacheProviderInterface $cache): void
    {
        $array = [1,2,3];
        $cache->setArray('testKey', $array);
        $value = $cache->getArray('testKey');

        $this->assertNotNull($value);
        $this->assertTrue(is_array($value), 'Unexpected type '.gettype($value));
        $this->assertEquals($value,$array);
    }

    /**
     * @dataProvider getCacheProvider
     *
     * @param CacheProviderInterface $cache
     */
    public function testGetInt(CacheProviderInterface $cache): void
    {
        $cache->setInt('testKey', 5);
        $value = $cache->getInt('testKey');

        $this->assertNotNull($value);
        $this->assertTrue(is_int($value), 'Unexpected type '.gettype($value));
        $this->assertEquals($value,5);
    }

    /**
     * @dataProvider getCacheProvider
     *
     * @param CacheProviderInterface $cache
     */
    public function testClear(CacheProviderInterface $cache): void
    {
        $cache->set('testKey1', 'vection');
        $cache->set('testKey2', 'vection');
        $cache->clear();

        $this->assertFalse($cache->contains('testKey1'));
        $this->assertFalse($cache->contains('testKey2'));
    }

    /**
     * @dataProvider getCacheProvider
     *
     * @param CacheProviderInterface $cache
     */
    public function testContains(CacheProviderInterface $cache): void
    {
        $cache->set('testKey', 'vection');

        $this->assertTrue($cache->contains('testKey'));
    }

    /**
     * @dataProvider getCacheProvider
     *
     * @param CacheProviderInterface $cache
     */
    public function testGetObject(CacheProviderInterface $cache): void
    {
        $object = new stdClass();
        $cache->setObject('testKey', $object);
        $value = $cache->getObject('testKey');

        $this->assertNotNull($value);
        $this->assertTrue(is_object($value), 'Unexpected type '.gettype($value));
        $this->assertEquals($value, $object);
    }

    /**
     * @dataProvider getCacheProvider
     *
     * @param CacheProviderInterface $cache
     */
    public function testGet(CacheProviderInterface $cache): void
    {
        $cache->set('testKey', 'vection');
        $value = $cache->get('testKey');

        $this->assertNotNull($value);
        $this->assertEquals($value, 'vection');
    }
}
