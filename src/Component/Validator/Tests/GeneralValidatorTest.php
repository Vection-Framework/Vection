<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Tests;

use PHPUnit\Framework\TestCase;
use Vection\Component\Validator\Validator\GeneralValidator;

/**
 * Class ValidatorTest
 *
 * @package Vection\Component\Validator\Tests
 */
class GeneralValidatorTest extends TestCase
{
    /**
     * @var GeneralValidator
     */
    private static $validator;


    public static function setUpBeforeClass()
    {
        self::$validator = new GeneralValidator();
    }

    public function testValidAlnum()
    {
        self::$validator->alnum('abc123');
        $this->assertTrue(true);
    }

    /**
     * @expectedException \Vection\Component\Validator\Exception\ValidationFailedException
     */
    public function testInvalidAlnum()
    {
        self::$validator->alnum('abc!123');
    }
}
