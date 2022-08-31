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

declare(strict_types=1);

namespace Vection\Contracts\Validator\Schema;

/**
 * Interface JsonTypeInterface
 *
 * @package Vection\Contracts\Validator\Schema
 * @author  David Lung <vection@davidlung.de>
 */
interface PropertyInterface
{
    /**
     * @return string|null
     */
    public function getName(): ? string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * @return bool
     */
    public function isNullable(): bool;

    /**
     * @param mixed[] $schema
     *
     * @throws SchemaExceptionInterface
     */
    public function evaluate(array $schema): void;

    /**
     * @param float|mixed[]|int|string $value
     *
     * @throws PropertyExceptionInterface
     */
    public function validate(float|array|int|string $value): void;
}
