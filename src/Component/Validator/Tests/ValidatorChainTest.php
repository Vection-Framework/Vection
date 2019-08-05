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
use Vection\Component\Validator\Validator\AlphaNumeric;
use Vection\Component\Validator\Validator\BetweenLength;
use Vection\Component\Validator\ValidatorChain;

/**
 * Class ValidatorTest
 *
 * @package Vection\Component\Validator\Tests
 */
class ValidatorChainTest extends TestCase
{

    /**
     *
     */
    public function testVerifySuccess()
    {
        $chain = new ValidatorChain();

        $chain('a')
            ->alphaNumeric()
            ->betweenLength(0, 7);

        $chain('b')
            ->alphaNumeric()
            ->betweenLength(0, 7);

        $chain('ac')
            ->nullable()
            ->alphaNumeric()
            ->betweenLength(0, 7);

        $chain->verify([
            'a' => 'abc123',
            'b' => 'abc123',
            'c' => null,
        ]);

        $this->assertEmpty($chain->getViolations());
    }

    /**
     *
     */
    public function testVerifyFail()
    {
        $chain = new ValidatorChain();

        $chain('a')
            ->alphaNumeric()
            ->betweenLength(0, 7);

        $chain('b')
            ->alphaNumeric()
            ->betweenLength(0, 7);

        $chain->verify([
            'a' => 'abc12345',
            'b' => '1abc23',
        ]);

        $violations = $chain->getViolations();

        $this->assertNotEmpty($violations);

        $violationA = (new BetweenLength(0, 7))->validate('abc12345');
        $violationB = (new AlphaNumeric())->validate('1abc23');

        $this->assertEquals($violationA->getMessage(), $violations['a']->getMessage());
        $this->assertEquals($violationB->getMessage(), $violations['b']->getMessage());
    }
}
