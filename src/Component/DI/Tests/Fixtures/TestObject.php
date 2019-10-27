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

namespace Vection\Component\DI\Tests\Fixtures;

use Vection\Component\DI\Annotations\Inject;
use Vection\Component\DI\Traits\AnnotationInjection;
use Vection\Component\DI\Traits\ContainerAwareTrait;

/**
 * Class TestObject
 *
 * @package Vection\Component\DI\Tests\Fixtures
 */
class TestObject implements InterfaceInjectedObjectInterface
{
    use AnnotationInjection, ContainerAwareTrait;

    /**
     * @Inject("Vection\Component\DI\Tests\Fixtures\AnnotationInjectedObject")
     * @var AnnotationInjectedObject
     */
    protected $annotationInjectedObject;

    /** @var ConstructorInjectedObject */
    private $constructorInjectedObject;

    /** @var ExplicitInjectedObject */
    private $explicitInjectedObject;

    /** @var InterfaceInjectedObject */
    protected $logger;

    /**
     * TestObject constructor.
     *
     * @param ConstructorInjectedObject $constructorInjectedObject
     */
    public function __construct(ConstructorInjectedObject $constructorInjectedObject)
    {
        $this->constructorInjectedObject = $constructorInjectedObject;
    }

    /**
     * @param ExplicitInjectedObject $o
     */
    public function __inject(ExplicitInjectedObject $o): void
    {
        $this->explicitInjectedObject = $o;
    }

    public function getExplicitInjectedObject()
    {
        return $this->explicitInjectedObject;
    }

    public function getAnnotationInjectedObject()
    {
        return $this->annotationInjectedObject;
    }

    public function getInterfaceInjectedObject()
    {
        return $this->logger;
    }

    public function setInterfaceInjectedObject($logger)
    {
        $this->logger = $logger;
    }
}
