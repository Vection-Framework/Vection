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

namespace Vection\Component\CQRS\Tests\Fixtures;

use Vection\Component\CQRS\Command\Command;
use Vection\Contracts\Validator\ValidationChainInterface;

/**
 * Class TestCommand
 *
 * @package Vection\Component\CQRS\Tests\Fixtures
 */
class TestCommand extends Command
{

    /**
     * Defines constraints for a set of data.
     *
     * @param ValidationChainInterface $chain
     */
    public function defineValidation(ValidationChainInterface $chain): void
    {
        // TODO: Implement defineValidation() method.
    }
}