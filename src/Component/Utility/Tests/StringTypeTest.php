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

namespace Vection\Component\Utility\Tests;


use PHPUnit\Framework\TestCase;
use Vection\Component\Utility\Lang\StringType;

class StringTypeTest extends TestCase
{

    public function testTitle()
    {
        $s = new StringType('äpfel siNd wiE Birnen! richtig?');
        $this->assertEquals('Äpfel Sind Wie Birnen! Richtig?', $s->title());
    }

    public function testStartsWith()
    {
        $s = new StringType('äpfel siNd wiE Birnen! richtig?');
        $this->assertTrue($s->startsWith('äpfel siN '));
    }

    public function testIsLower()
    {
        $s = new StringType('äpfel 11');
        $this->assertTrue($s->isLower());
        $s = new StringType('Äpfel 11');
        $this->assertFalse($s->isLower());
    }

    public function testIsNumeric()
    {
        $s = new StringType('111');
        $this->assertTrue($s->isNumeric());
        $s->append(' A');
        $this->assertFalse($s->isNumeric());
    }

    public function testUpper()
    {
        $s = new StringType('ÄPFEL');
        $this->assertFalse($s->isLower());
    }

    public function testCount()
    {

    }

    public function testCapitalize()
    {

    }

    public function testOrd()
    {

    }

    public function testAppend()
    {

    }

    public function testSplit()
    {

    }

    public function testReversed()
    {
        $s = new StringType('äpfel siNd');
        $this->assertEquals('dNis lefpä', $s->reversed());
        $this->assertNotEquals('dnis lefpä', $s->reversed());
    }

    public function testIsAlphaNumeric()
    {
        $s = new StringType('apfel999');
        $this->assertTrue($s->isAlphaNumeric());

        $s = new StringType('äpfel999');
        $this->assertFalse($s->isAlphaNumeric());
// TODO: failed not multi byte safe.
    }

    public function testEncode()
    {

    }

    public function testContains()
    {
        $s = new StringType('äpfel siNd wiE Birnen! richtig?');
        $this->assertTrue($s->contains('äpfel siNd '));
        $this->assertFalse($s->contains('äpfel sind '));
    }

    public function testEquals()
    {
        $s = new StringType('äpfel siNd wiE Birnen! richtig?');
        $this->assertTrue($s->equals('äpfel siNd wiE Birnen! richtig?'));
        $this->assertFalse($s->equals('äpfel siNd wie Birnen! richtig?'));
    }

    public function testLen()
    {
        $s = new StringType('äpfel');
        $this->assertEquals(5, $s->len());
        $this->assertNotEquals(6, $s->len());
    }

    public function testSnake()
    {

    }

    public function testJoin()
    {

    }

    public function testIsUpper()
    {

    }

    public function testPos()
    {

    }

    public function testRpos()
    {

    }

    public function testEndsWith()
    {

    }

    public function testLimit()
    {

    }

    public function testPrepend()
    {

    }

    public function testCamalize()
    {

    }

    public function testIsAlpha()
    {

    }

    public function testIsDigit()
    {

    }

    public function testNotContains()
    {

    }

    public function testReplace()
    {

    }

    public function testLower()
    {

    }

    public function testRepeat()
    {

    }
}
