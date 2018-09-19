<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) Vection <project@vection.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use function Vection\Component\DI\def;

return [

    def( \Vection\Component\DI\Tests\Fixtures\TestObject::class )
        ->instance(function(){
            return new \Vection\Component\DI\Tests\Fixtures\TestObject(true);
        })
    ,

];