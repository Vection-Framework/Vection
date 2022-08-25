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

namespace Vection\Component\Messenger;

use Vection\Component\Common\Crypto;
use Vection\Contracts\Messenger\MessageIdGeneratorInterface;

/**
 * Class MessageIdGenerator
 *
 * @package Vection\Component\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
class MessageIdGenerator implements MessageIdGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate(): string
    {
        return Crypto::identity('msg', 13);
    }
}
