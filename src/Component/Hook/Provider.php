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

/*
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Hook;

/**
 * Class Provider
 *
 * @package Vection\Component\Hook
 */
class Provider
{

    /**
     * @var string
     */
    protected string $scope;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var string
     */
    protected string $api;

    /**
     * Provider constructor.
     *
     * @param string $scope
     * @param string $name
     * @param string $path
     * @param string $api
     */
    public function __construct(string $scope, string $name, string $path, string $api)
    {
        $this->name  = $name;
        $this->path  = $path;
        $this->api   = $api;
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getApi(): string
    {
        return $this->api;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }
}
