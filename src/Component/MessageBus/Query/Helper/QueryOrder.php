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

namespace Vection\Component\MessageBus\Query\Helper;

use Vection\Contracts\MessageBus\Query\Helper\QueryOrderInterface;

/**
 * Class QueryOrder
 *
 * @package Vection\Component\MessageBus\Query\Helper
 */
class QueryOrder implements QueryOrderInterface
{
    public const ASC  = 0;
    public const DESC = 1;

    /** @var string */
    private $field;

    /** @var integer */
    private $direction;

    /**
     * QueryOrder constructor.
     *
     * @param string $field     The name of the field.
     * @param int    $direction Use QueryOrder constants, 0 = ASC, 1 = DESC
     */
    public function __construct(string $field, int $direction = 0)
    {
        $this->field     = $field;
        $this->direction = $direction;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return int
     */
    public function getDirection(): int
    {
        return $this->direction;
    }

    /**
     * @return string
     */
    public function getDirectionString(): string
    {
        return $this->direction === 0 ? 'ASC' : 'DESC';
    }

}
