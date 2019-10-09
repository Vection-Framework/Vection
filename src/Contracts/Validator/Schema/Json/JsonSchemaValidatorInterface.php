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
 * Interface JsonSchemaValidator
 *
 * @package Vection\Contracts\Validator\Schema\Json
 *
 * @author David Lung <vection@davidlung.de>
 */
interface JsonSchemaValidatorInterface
{
    /**
     * @param string $path
     *
     * @return array
     *
     * @throws JsonPropertyExceptionInterface
     * @throws JsonSchemaExceptionInterface
     */
    public function validateFile(string $path): array;

    /**
     * @param string $json
     *
     * @return array
     *
     * @throws JsonPropertyExceptionInterface
     * @throws JsonSchemaExceptionInterface
     */
    public function validateString(string $json): array;

    /**
     * @param array $data
     *
     * @return array
     *
     * @throws JsonPropertyExceptionInterface
     * @throws JsonSchemaExceptionInterface
     */
    public function validateArray(array $data): array;
}