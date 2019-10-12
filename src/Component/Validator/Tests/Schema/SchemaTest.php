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

namespace Vection\Component\Validator\Tests\Schema;

use PHPUnit\Framework\TestCase;
use Vection\Component\Validator\Schema\Schema;
use Vection\Contracts\Validator\Schema\SchemaExceptionInterface;

/**
 * Class SchemaTest
 *
 * @package Vection\Component\Validator\Tests\Schema
 */
class SchemaTest extends TestCase
{

    /**
     * @throws SchemaExceptionInterface
     */
    public function testEvaluate(): void
    {
        $schema = new Schema(__DIR__.'/schema.json');
        $schema->evaluate();

        $this->assertTrue(true);
    }
}
