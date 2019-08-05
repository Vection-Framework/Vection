<?php declare(strict_types=1);

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\MessageBus\Command;

use Vection\Component\MessageBus\Identifier;
use Vection\Component\MessageBus\Message;
use Vection\Component\MessageBus\Payload;
use Vection\Contracts\MessageBus\Command\CommandInterface;
use Vection\Contracts\MessageBus\IdentifierInterface;
use Vection\Contracts\MessageBus\PayloadInterface;

/**
 * Class Command
 *
 * @package Vection\Component\MessageBus\Command
 */
abstract class Command extends Message implements CommandInterface
{

    /**
     * An instance of Identifier that can contains the
     * identifier for the requested resource.
     *
     * @var IdentifierInterface
     */
    protected $identifier;

    /**
     * Command constructor.
     *
     * @param int|string|null  $identifier
     * @param PayloadInterface $payload
     */
    public function __construct(PayloadInterface $payload = null, $identifier = null)
    {
        parent::__construct($payload);

        $this->identifier = new Identifier($identifier);
        $this->payload = $payload ?: new Payload();
    }

    /**
     * @inheritDoc
     */
    public function getIdentity(): IdentifierInterface
    {
        return $this->identifier;
    }

}