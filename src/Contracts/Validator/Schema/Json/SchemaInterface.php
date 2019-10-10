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

namespace Vection\Contracts\Validator\Schema\Json;

/**
 * Interface SchemaInterface
 *
 * @package Vection\Contracts\Validator\Schema\Json
 *
 * @author David Lung <vection@davidlung.de>
 */
interface SchemaInterface
{
    /**
     * @param string $name
     * @param array  $schema
     */
    public function addTemplate(string $name, array $schema): void;

    /**
     * @return JsonPropertyInterface
     *
     * @throws JsonSchemaExceptionInterface
     */
    public function evaluate(): JsonPropertyInterface;
}