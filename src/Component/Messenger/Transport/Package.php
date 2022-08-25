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

declare(strict_types=1);

namespace Vection\Component\Messenger\Transport;

use ReturnTypeWillChange;
use Vection\Contracts\Messenger\Transport\PackageInterface;

/**
 * Class Package
 *
 * @package Vection\Component\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
class Package implements PackageInterface
{
    /**
     * @var string[]
     */
    protected array $meta;

    /**
     * @var string[]
     */
    protected array $headers;

    /**
     * @var string
     */
    protected string $message;

    /**
     * TransportData constructor.
     *
     * @param array  $meta
     * @param array  $headers
     * @param string $message
     */
    public function __construct(array $meta, array $headers, string $message)
    {
        $this->meta    = $meta;
        $this->headers = $headers;
        $this->message = $message;
    }

    /**
     * @inheritDoc
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    #[ReturnTypeWillChange] public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'meta'    => $this->meta,
            'headers' => $this->headers,
            'message' => $this->message
        ];
    }
}
