<?php
/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\MessageBus\Query\Helper;

use Vection\Contracts\MessageBus\Query\Helper\QueryFilterInterface;

/**
 * Class QueryFilter
 *
 * @package Vection\Component\MessageBus\Query\Helper
 */
class QueryFilter implements QueryFilterInterface
{
    /**
     * The applied filter which will be returned.
     *
     * @var string
     */
    protected $filter;

    /**
     * All occurred values from the applied filter.
     *
     * @var string[]
     */
    protected $values;

    /**
     * @param string $filter
     *
     * @throws \InvalidArgumentException
     */
    public function apply(string $filter): void
    {
        # e.g.: ?filter=name="peter" AND (gender="female" OR age="33") OR color IS EMPTY
        $validate = '/\(?([a-zA-Z_.0-9-]+)\s?((=\s?"([^"]+)")|IS\s+(NOT\s+)?EMPTY)\)?(\s+(AND|OR)\s+)?/';

        if( ! \preg_match_all($validate, $filter, $matches) ){
            throw new \InvalidArgumentException('Filter syntax error');
        }

        # Extract all values from filter string
        $this->values = array_filter(array_combine($matches[1], $matches[4]));

        # Replace all values with ? for using prepared statement
        $filter = preg_replace('/("[^"]+")/', '?', $filter);

        # Replace all public fields with internal fields
        preg_replace_callback('/([a-zA-Z_.0-9-]+)(\s?=|\s+IS)/', function($match){
            return ($this->fieldMapping[$match[1]]??$match[1]).$match[2];
        }, $filter);
    }

    /**
     * Returns one filter values by its name.
     *
     * @param string $name
     *
     * @return string
     */
    public function getValue(string $name): string
    {
        return $this->values[$name] ?? null;
    }

    /**
     * Returns all filter values as assoc array with name as key.
     *
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * Returns the applied filter as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->filter;
    }
}