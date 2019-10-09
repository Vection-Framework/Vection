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

declare(strict_types = 1);

namespace Vection\Component\Validator\Schema\Json\Type;

use Vection\Component\Validator\Schema\Json\Exception\MissingPropertyException;
use Vection\Component\Validator\Schema\Json\JsonType;
use Vection\Contracts\Validator\Schema\Json\JsonPropertyExceptionInterface;

/**
 * Class JsonArray
 *
 * @package Vection\Component\Validator\Schema\Json\Type
 *
 * @author David Lung <vection@davidlung.de>
 */
class JsonArray extends JsonType
{
    /**
     * @var JsonType
     */
    protected $element;

    /**
     * @inheritDoc
     */
    protected function onEvaluate(array $schema): void
    {
        $this->element = $this->createType($schema['@element']['@type']);
        $this->element->evaluate($schema['@element']);
    }

    /**
     * @inheritDoc
     *
     * @throws MissingPropertyException
     */
    public function validate($values): void
    {
        if( $this->isRequired() && count($values) === 0 ){
            throw new MissingPropertyException($this->name.'.0');
        }

        foreach( $values as $name => $value ){

            $this->element->setName((string)$name);

            try{
                $this->element->validate($value);
            }
            catch(JsonPropertyExceptionInterface $e){
                $e->withProperty($this->name);
            }

        }
    }
}