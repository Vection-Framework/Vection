<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use function Vection\Component\DI\set;

return [

    # Object factory (closure)
    set(Vection\Component\DI\Tests\Fixtures\TestObject::class)
        ->factory(function(){
            return new Vection\Component\DI\Tests\Fixtures\TestObject();
        })
    ,

    # Interface injection (setter)
    set(Vection\Component\DI\Tests\Fixtures\InterfaceInjectedObjectInterface::class)
        ->inject(Vection\Component\DI\Tests\Fixtures\InterfaceInjectedObject::class)
    ,
];