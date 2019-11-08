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

declare(strict_types = 1);

namespace Vection\Component\Messenger\Service\Command\Transport;

use Vection\Component\Messenger\Transport\Processor;

/**
 * Class CommandProcessor
 *
 * @package Vection\Component\Messenger\Service\Command\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
class CommandProcessor extends Processor
{
    /**
     * @inheritDoc
     */
    public function getTag(): string
    {
        return 'command';
    }
}