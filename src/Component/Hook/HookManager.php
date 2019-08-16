<?php declare(strict_types=1);

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Hook;

use Vection\Component\Hook\Exception\HookException;

/**
 * Class HookManager
 *
 * @package Vection\Component\Hook
 */
class HookManager
{
    /** @var array */
    protected $configuration;

    /** @var HookContext[][] */
    protected $hookContexts;

    /**
     * @param string $path
     */
    public function loadConfig(string $path)
    {
        if( ! file_exists($path) ){
            throw new \RuntimeException("Cannot find hook configuration by path '$path'");
        }

        if( in_array(pathinfo($path, PATHINFO_EXTENSION), ['yml','yaml']) ){
            if( ! function_exists('yaml_parse_file') ){
                throw new \RuntimeException(
                    "Cannot parse hook config file: Please install yaml extension 
                    to parse yaml configuration or use json format."
                );
            }

            $this->configuration = yaml_parse_file($path);
        }

        if( pathinfo($path, PATHINFO_EXTENSION) === 'json' ){

            $this->configuration = json_decode(file_get_contents($path), true);

            if( json_last_error() !== JSON_ERROR_NONE ){
                throw new \RuntimeException(
                    "Invalid hook configuration ($path): " . json_last_error_msg()
                );
            }
        }

        if( ! $this->configuration ){
            throw new \RuntimeException(
                "Unsupported hook configuration file. Please use yaml or json format."
            );
        }

        foreach( $this->configuration as $scope => &$config ){

            $p = $config['hooks']['path'];
            if( strpos($p, '.') === 0 ){
                $config['hooks']['path'] = dirname($path) . '/'.$p;
            }

            $pattern = "{$config['hooks']['path']}/*/{$scope}.{yml,yaml,json}";

            foreach( glob($pattern, GLOB_BRACE) as $path ){

            }

        }


    }

    /**
     * @param string $scope
     *
     * @return bool
     */
    public function hasScope(string $scope): bool
    {
        return isset($this->configuration[$scope]);
    }

    /**
     * @param string $scope
     * @param string $hookPointName
     *
     * @return bool
     */
    public function hasHookPoint(string $scope, string $hookPointName): bool
    {
        return isset($this->configuration[$scope][$hookPointName]);
    }

    /**
     * @param string $scope
     * @param string $hookPointName
     *
     * @return HookLoader
     *
     * @throws HookException
     */
    public function getLoader(string $scope, string $hookPointName): HookLoader
    {
        if( ! isset($this->configuration[$scope]) ){
            throw new HookException("Cannot find given scope '$scope'.");
        }

        if( ! isset($this->configuration[$scope]['providers'][$hookPointName]) ){
            throw new HookException("Cannot find given hook point '$hookPointName'.");
        }

        $provider = new Provider(
            $scope,
            $hookPointName,
            $this->configuration[$scope]['hooks']['path'],
            $this->configuration[$scope]['providers'][$hookPointName]['api']
        );

        return new HookLoader($provider, $this->hookContexts[$scope]);
    }
}