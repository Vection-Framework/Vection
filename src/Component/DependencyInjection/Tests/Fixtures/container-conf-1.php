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

use function Vection\Component\DependencyInjection\set;

return [

    # Object factory (closure)
    set(Vection\Component\DependencyInjection\Tests\Fixtures\TestObject::class)
        ->factory(
            function(){
            return new Vection\Component\DependencyInjection\Tests\Fixtures\TestObject(
                new Vection\Component\DependencyInjection\Tests\Fixtures\ConstructorInjectedObject()
            );
            }
        )
    ,

    # Interface injection (setter)
    set(Vection\Component\DependencyInjection\Tests\Fixtures\InterfaceInjectedObjectInterface::class)
        ->inject(Vection\Component\DependencyInjection\Tests\Fixtures\InterfaceInjectedObject::class)
    ,
];
