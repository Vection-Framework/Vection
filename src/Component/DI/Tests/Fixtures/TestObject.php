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
    use AnnotationInjection;
    use ContainerAwareTrait;

    /**
     * @Inject("Vection\Component\DI\Tests\Fixtures\AnnotationInjectedObject")
     * @var AnnotationInjectedObject
     */
    protected AnnotationInjectedObject  $annotationInjectedObject;
    private   ConstructorInjectedObject $constructorInjectedObject;
    private   ExplicitInjectedObject    $explicitInjectedObject;
    protected InterfaceInjectedObject   $logger;

    /**
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

    /**
     * @return ExplicitInjectedObject
     */
    public function getExplicitInjectedObject(): ExplicitInjectedObject
    {
        return $this->explicitInjectedObject;
    }

    /**
     * @return AnnotationInjectedObject
     */
    public function getAnnotationInjectedObject(): AnnotationInjectedObject
    {
        return $this->annotationInjectedObject;
    }

    /**
     * @return InterfaceInjectedObject
     */
    public function getInterfaceInjectedObject(): InterfaceInjectedObject
    {
        return $this->logger;
    }

    /**
     * @param InterfaceInjectedObject $logger
     *
     * @return void
     */
    public function setInterfaceInjectedObject($logger): void
    {
        $this->logger = $logger;
    }
}
