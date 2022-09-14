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

namespace Vection\Component\DependencyInjection;

use Closure;
use Vection\Contracts\DependencyInjection\InstructionInterface;

/**
 * Class Instruction
 *
 * @package Vection\Component\DependencyInjection
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Instruction implements InstructionInterface
{
    protected string       $className;
    protected bool         $isShared = true;
    protected Closure|null $factory  = null;
    protected string|null  $by = null;
    /** @var array<string, string> */
    protected array        $setter = [];

    /**
     * Instruction constructor.
     *
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->className = trim($className, '\\');
    }

    /**
     * @param callable|Closure $closure
     *
     * @return static
     */
    public function viaFactory(callable|Closure $closure): static
    {
        $this->factory = ($closure instanceof Closure ? $closure : $closure(...));
        return $this;
    }

    /**
     * @param string $className
     * @param string $setterMethodName
     *
     * @return static
     */
    public function viaSetter(string $setterMethodName, string $className): static
    {
        $this->setter[$setterMethodName] = $className;

        return $this;
    }

    /**
     * @param string $className
     *
     * @return static
     */
    public function by(string $className): static
    {
        $this->by = $className;
        return $this;
    }

    /**
     * @param bool $shared
     *
     * @return static
     */
    public function asShared(bool $shared): static
    {
        $this->isShared = $shared;
        return $this;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return array<string, string>
     */
    public function getSetterInjections(): array
    {
        return $this->setter;
    }

    /**
     * @return string|null
     */
    public function getBy(): string|null
    {
        return $this->by;
    }

    /**
     * @return Closure|null
     */
    public function getFactory(): Closure|null
    {
        return $this->factory;
    }

    /**
     * @return bool
     */
    public function isShared(): bool
    {
        return $this->isShared;
    }
}
