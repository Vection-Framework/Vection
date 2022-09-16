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

use Vection\Contracts\Cache\CacheAwareInterface;

/**
 * Interface ResolverInterface
 *
 * @package Vection\Contracts\DependencyInjection
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
interface ResolverInterface extends CacheAwareInterface
{
    /**
     * @param InstructionInterface $instruction
     */
    public function addInstruction(InstructionInterface $instruction): void;

    /**
     * @param string $className
     *
     * @return InstructionInterface|null
     */
    public function getInstruction(string $className): InstructionInterface|null;

    /**
     * @param string $className
     *
     * @return array<string, mixed>|null
     */
    public function getClassDependencies(string $className): array|null;

    /**
     * Resolves all dependencies of the given class.
     *
     * @param string $className
     *
     * @return array<string, mixed> An array that contains dependency information.
     */
    public function resolveDependencies(string $className): array;
}
