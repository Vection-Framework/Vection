<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Tests;

use PHPUnit\Framework\TestCase;
use Vection\Component\Validator\Tests\Fixtures\CustomValidator;
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
    public function testVerifyFail(): void
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

        $this->assertNotNull($violationA);
        $this->assertNotNull($violationB);

        if( $violationA !== null ){
            $this->assertEquals($violationA->getMessage(), $violations['a']->getMessage());
        }

        if( $violationB !== null ){
            $this->assertEquals($violationB->getMessage(), $violations['b']->getMessage());
        }
    }

    /**
     *
     */
    public function testCustomValidator(): void
    {
        $chain = new ValidatorChain();

        $chain('a')
            ->notNull()
            ->use(new CustomValidator())
            ->alphaNumeric()
        ;

        $chain('b')
        ->notNull()
        ->use(new CustomValidator())
        ->alphaNumeric()
    ;

        $chain->verify([
            'a' => 'abcxxxdef',
            'b' => 'abcdef'
        ]);

        $violations = $chain->getViolations();

        $this->assertSame(count($violations), 1);
    }

}
