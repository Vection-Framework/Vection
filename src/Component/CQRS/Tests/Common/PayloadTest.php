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

namespace Vection\Component\CQRS\Tests;

use Vection\Component\CQRS\Common\Payload;
use Vection\Contracts\CQRS\Common\PayloadInterface;

/**
 * Class PayloadTest
 *
 * @package Vection\Component\CQRS\Tests
 */
class PayloadTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getPayloadData
     */
    public function testPop($data)
    {
        $payload = new Payload($data);
        $value = $payload->pop('A');

        $this->assertEquals('D', $value);
        $this->assertNull($payload->get('A'), 'An entry with key "A" should not exists after pop it.');
    }

    /**
     * @dataProvider getPayloadData
     */
    public function testGetPayload($data)
    {
        $payload = new Payload($data);
        $newPayload = $payload->getPayload('A');

        $this->assertTrue($newPayload instanceof PayloadInterface);
    }

    /**
     * @dataProvider getPayloadData
     */
    public function testToArray($data)
    {
        $payload = new Payload($data);
        $this->assertSame($data, $payload->toArray());
    }

    public function testSet()
    {
        $payload = new Payload();
        $payload->set('A', 'D');
        $this->assertEquals('D', $payload->get('A'));
    }

    /**
     * @dataProvider getPayloadData
     */
    public function testGet($data)
    {
        $payload = new Payload($data);
        $this->assertEquals('D', $payload->get('A'));
    }

    public function getPayloadData()
    {
        return [
            0 => [
                0 => [
                    0 => 'A',
                    1 => 'B',
                    2 => 'C',
                    'A' => 'D',
                    'E' => 'F'
                ]
            ]
        ];
    }
}
