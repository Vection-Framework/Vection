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

namespace Vection\Contracts\Validator\Schema;

/**
 * Interface JsonSchemaValidator
 *
 * @package Vection\Contracts\Validator\Schema
 *
 * @author David Lung <vection@davidlung.de>
 */
interface SchemaValidatorInterface
{
    /**
     * @param string $path
     *
     * @return array
     *
     * @throws PropertyExceptionInterface
     * @throws SchemaExceptionInterface
     */
    public function validate(string $path): array;

    /**
     * @param string $json
     *
     * @return array
     *
     * @throws PropertyExceptionInterface
     * @throws SchemaExceptionInterface
     */
    public function validateJsonString(string $json): array;

    /**
     * @param string $json
     *
     * @return array
     *
     * @throws PropertyExceptionInterface
     * @throws SchemaExceptionInterface
     */
    public function validateYamlString(string $json): array;

    /**
     * @param array $data
     *
     * @throws PropertyExceptionInterface
     * @throws SchemaExceptionInterface
     */
    public function validateArray(array $data): void;
}