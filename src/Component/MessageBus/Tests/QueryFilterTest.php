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
}
