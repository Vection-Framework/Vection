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

/*
 * This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  (c) David Lung <vection@davidlung.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\MessageBus\Query;

use Vection\Component\MessageBus\Message;
use Vection\Contracts\MessageBus\PayloadInterface;
use Vection\Contracts\MessageBus\Query\QueryInterface;

/**
 * Class Query
 *
 * @package Vection\Component\MessageBus\Query
 */
abstract class Query extends Message implements QueryInterface
{

    /**
     * Query constructor.
     *
     * @param PayloadInterface $payload
     */
    public function __construct(PayloadInterface $payload = null)
    {
        parent::__construct($payload);
    }

}
