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

namespace Vection\Component\DependencyInjection\Tests\Fixtures;

use Vection\Component\DependencyInjection\Attributes\Inject;
use Vection\Component\DependencyInjection\Traits\AnnotationInjection;
use Vection\Component\DependencyInjection\Traits\ContainerAwareTrait;

/**
 * Class TestObject
 *
 * @package Vection\Component\DependencyInjection\Tests\Fixtures
 */
class TestObject implements InterfaceInjectedObjectInterface
{
    use AnnotationInjection;
    use ContainerAwareTrait;

    #[Inject] protected AnnotationInjectedObject $annotationInjectedObject;
    private   ConstructorInjectedObject          $constructorInjectedObject;
    private   ExplicitInjectedObject    $explicitInjectedObject;
    protected InterfaceInjectedObject   $logger;

    /**
     * @param ConstructorInjectedObject $constructorInjectedObject
     */
    public function __construct(ConstructorInjectedObject $constructorInjectedObject)
    {
        $this->constructorInjectedObject = $constructorInjectedObject;
    }

    public function __inject(ExplicitInjectedObject $o): void
    {
        $this->explicitInjectedObject = $o;
    }

    public function getExplicitInjectedObject(): ExplicitInjectedObject
    {
        return $this->explicitInjectedObject;
    }

    public function getAnnotationInjectedObject(): AnnotationInjectedObject
    {
        return $this->annotationInjectedObject;
    }

    public function getInterfaceInjectedObject(): InterfaceInjectedObject
    {
        return $this->logger;
    }

    public function setInterfaceInjectedObject($logger): void
    {
        $this->logger = $logger;
    }
}
