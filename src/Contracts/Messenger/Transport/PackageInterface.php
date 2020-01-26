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

namespace Vection\Contracts\Messenger\Transport;

use JsonSerializable;

/**
 * Interface PackageInterface
 *
 * @package Vection\Contracts\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface PackageInterface extends JsonSerializable
{
    /**
     * @return array
     */
    public function getMeta(): array;

    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return array
     */
    public function toArray(): array;
}
