<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Bjoern Klemm <vection@bjoernklemm.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Utility\Calendar\Tests;

use PHPUnit\Framework\TestCase;
use Vection\Component\Utility\Calendar\Calendar;

class CalendarTest extends TestCase
{

    public function testGet()
    {

    }

    public function testAdd()
    {
        $c = new Calendar();
        $c->set(Calendar::DAY_OF_MONTH,31);
        $c->set(Calendar::MONTH,'August');
        $c->set(Calendar::YEAR,1999);

        $c->add(Calendar::MONTH_OF_YEAR,13);

        $this->assertEquals('2000-09-30',(string)$c);
    }

    public function testSet()
    {
        $c = new Calendar();
        $c->set(Calendar::DAY_OF_MONTH,31);
        $c->set(Calendar::MONTH,'August');
        $c->set(Calendar::YEAR,1999);

        echo $c;
    }
}
