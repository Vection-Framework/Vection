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

use InvalidArgumentException;
use Vection\Contracts\MessageBus\Query\Helper\QueryFilterInterface;

/**
 * Class QueryFilter
 *
 * @package Vection\Component\MessageBus\Query\Helper
 */
class QueryFilter implements QueryFilterInterface
{

    /**
     * Mapping from public to internal field names.
     *
     * @var string[]
     */
    protected $fieldMapping;

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
     * QueryFilter constructor.
     *
     * @param array $fieldMapping
     *
     */
    public function __construct(array $fieldMapping = [])
    {
        $this->fieldMapping = $fieldMapping;
    }

    /**
     * Apply filter
     *
     * @param string $filter
     *
     * @throws InvalidArgumentException
     */
    public function apply(string $filter): void
    {
        # e.g.: ?filter=name="peter" AND (gender="female" OR age="33") OR color IS NULL
        $pattern = '/\(?([\w-]+.{0,1})\s?(=|<=|>=|<|>|(IS\s+(NOT\s+)?(NULL)?)|(NOT\s+)?LIKE)\s?"([\w\s-]*)"\)?\s?(AND|OR)?/iU';

        if ( ! \preg_match_all($pattern, $filter, $matches) ) {
            throw new InvalidArgumentException('Filter syntax error');
        }

        # Extract all values from filter string
        $this->values = \array_filter(\array_combine($matches[1], $matches[7]));

        # Replace all values with ? for using prepared statement
        $filter = \preg_replace('/("[^"]+")/', '?', $filter);

        # Replace all public fields with internal fields
        $pattern = '/([\w-]+.{0,1})\s?(=|<=|>=|<|>|(IS\s+(NOT\s+)?(NULL)?)|(NOT\s+)?LIKE)\s?/iU';
        // @var string $filter
        $filter = \preg_replace_callback(
            $pattern,
            function($match){
            return ($this->fieldMapping[$match[1]] ?? $match[1]) . ' ' . $match[2];
            },
            $filter
        );

        $this->filter = $filter;
    }

    /**
     * Returns one filter values by its name.
     *
     * @param string $name
     *
     * @return string
     */
    public function getValue(string $name): ?string
    {
        return ($this->values[$name] ?? null);
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
