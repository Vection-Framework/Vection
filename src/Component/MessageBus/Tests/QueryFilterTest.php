<?php

namespace Vection\Component\MessageBus\Tests;

use PHPUnit\Framework\TestCase;
use Vection\Component\MessageBus\Query\Helper\QueryFilter;

/**
 * Class QueryFilterTest
 *
 * @package Vection\Component\MessageBus\Tests
 */
class QueryFilterTest extends TestCase
{

    /**
     * Test case apply OK
     */
    public function testApplyCaseOK()
    {
        $filter = 'name = "max mustermann" and age > "18" and (size < "180" or fingers >= "5") and time <= "987654321"';

        $queryFilter = new QueryFilter();
        $queryFilter->apply($filter);

        $this->assertEquals('name = ? and age > ? and (size < ? or fingers >= ?) and time <= ?', $queryFilter);
    }

    /**
     * Test case apply exception
     */
    public function testApplyCaseException()
    {
        $this->expectException('InvalidArgumentException');

        $filter = 'name = max mustermann and age > 18 and (size < 180 or fingers >= 5) and time <= 987654321';

        $queryFilter = new QueryFilter();
        $queryFilter->apply($filter);
    }

    public function testFieldMapping()
    {
        $filter = 'name = "max mustermann" and age > "18" and (size < "180" or fingers >= "5") and time <= "987654321"';

        $queryFilter = new QueryFilter();
        $queryFilter->apply($filter);

        $values = $queryFilter->getValues();

        $this->assertArrayHasKey('name', $values);
        $this->assertArrayHasKey('age', $values);
        $this->assertArrayHasKey('size', $values);
        $this->assertArrayHasKey('fingers', $values);
        $this->assertArrayHasKey('time', $values);

        $this->assertEquals('max mustermann', $values['name']);
        $this->assertEquals('18', $values['age']);
        $this->assertEquals('180', $values['size']);
        $this->assertEquals('5', $values['fingers']);
        $this->assertEquals('987654321', $values['time']);
    }
}
