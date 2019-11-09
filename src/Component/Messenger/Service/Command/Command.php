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

namespace Vection\Component\Messenger\Service\Command;

use Vection\Component\Messenger\MessagePayload;
use Vection\Contracts\Messenger\Service\Command\CommandInterface;

/**
 * Class Command
 *
 * @package Vection\Component\Messenger\Service\Command
 *
 * @author  David Lung <vection@davidlung.de>
 */
abstract class Command extends MessagePayload implements CommandInterface
{
    /**
     * This method is used by the method Command::getId() to
     * find the id value from command data. It uses the id name 'id'
     * by default and can be override by subclass if the id name differs from 'id'.
     *
     * @return string
     */
    protected function getIdName(): string
    {
        return 'id';
    }

    /**
     * @inheritDoc
     */
    final public function getId(): ?string
    {
        return $this->get($this->getIdName());
    }
}
