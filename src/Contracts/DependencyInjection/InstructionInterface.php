<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Contracts\DependencyInjection;

use Closure;

/**
 * Interface InstructionInterface
 *
 * @package Vection\Contracts\DependencyInjection
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
interface InstructionInterface
{
    /**
     * @param callable|Closure $closure
     *
     * @return static
     */
    public function viaFactory(callable|Closure $closure): static;

    /**
     * @param string $className
     * @param string $setterMethodName
     *
     * @return static
     */
    public function viaSetter(string $setterMethodName, string $className): static;

    /**
     * @param string $className
     *
     * @return static
     */
    public function by(string $className): static;

    /**
     * @param bool $shared
     *
     * @return static
     */
    public function asShared(bool $shared): static;

    /**
     * @return string
     */
    public function getClassName(): string;

    /**
     * @return array<string, string>
     */
    public function getSetterInjections(): array;

    /**
     * @return string|null
     */
    public function getBy(): string|null;

    /**
     * @return Closure|null
     */
    public function getFactory(): Closure|null;

    /**
     * @return bool
     */
    public function isShared(): bool;
}
