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
    private $provider;

    /**
     * @var array
     */
    private $contexts;

    /**
     * HookLoader constructor.
     *
     * @param Provider $provider
     * @param array    $contexts
     */
    public function __construct(Provider $provider, array $contexts)
    {
        $this->provider = $provider;
        $this->contexts = $contexts;
    }

    public function getHookContextList(): array
    {
        return [];
    }

    public function load(string $hookName): ?object
    {
        $path    = $this->provider->getPath();
        $scope   = $this->provider->getScope();
        $pattern = "{$path}/*/{$scope}.{yml,yaml,json}";

        foreach ( glob($pattern, GLOB_BRACE) as $path ) {
            $_path = $path;
        }

        return null;
    }

    public function loadAll(): array
    {
        return [];
    }
}
