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

namespace Vection\Component\Hook;

/**
 * Class HookLoader
 *
 * @package Vection\Component\Hook
 */
class HookLoader
{

    /**
     * @var Provider
     */
    private Provider $provider;

    /**
     * @var mixed[]
     */
    private array $contexts;

    /**
     * @param Provider $provider
     * @param mixed[]  $contexts
     */
    public function __construct(Provider $provider, array $contexts)
    {
        $this->provider = $provider;
        $this->contexts = $contexts;
    }

    /**
     * @return mixed[]
     */
    public function getHookContextList(): array
    {
        return [];
    }

    public function load(string $hookName): ?object
    {
        $path    = $this->provider->getPath();
        $scope   = $this->provider->getScope();
        $pattern = "$path/*/$scope.{yml,yaml,json}";

        foreach ( glob($pattern, GLOB_BRACE) as $path ) {
            $_path = $path;
        }

        return null;
    }

    /**
     * @return mixed[]
     */
    public function loadAll(): array
    {
        return [];
    }
}
