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
use Vection\Component\Validator\Schema\Json\JsonSchemaValidator;
use Vection\Component\Validator\Schema\Json\Schema;

/**
 * Class JsonSchemaValidatorTest
 *
 * @package Vection\Component\Validator\Tests\Schema
 */
class JsonSchemaValidatorTest extends TestCase
{
    public function testValidateArray()
    {
        $schema = new Schema(__DIR__.'/schema.json');
        $validator = new JsonSchemaValidator($schema);
        $validator->validateArray(
            json_decode(file_get_contents(__DIR__.'/test.json'), true)
        );

        $this->assertTrue(true);
    }
}
