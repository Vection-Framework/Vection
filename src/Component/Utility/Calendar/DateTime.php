<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Bjoern Klemm <vection@bjoernklemm.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Utility\Calendar;


use Vection\Component\Utility\Calendar\Exception\DateAssertion;
use Vection\Component\Utility\Calendar\Exception\InvalidDateException;

/**
 * Class DateTime
 *
 * @package Vection\Component\Utility\Calendar
 *
 * @author  Bjoern Klemm <vection@bjoernklemm.de>
 */
class DateTime extends \DateTime
{
    /** @var string  */
    public const UTC = 'UTC';

    /**
     * Creates an immutable DateTimeObject
     *
     * @param string $date
     *
     * @return \DateTimeImmutable
     */
    public static function create(string $date): \DateTimeImmutable
    {
        $tz        = new \DateTimeZone(self::UTC);
        $immutable = \DateTimeImmutable::createFromFormat(self::W3C, $date, $tz);

        InvalidDateException::isValid($immutable);

        return $immutable;
    }

    /**
     * @return \DateTimeImmutable
     */
    public static function utcNow(): \DateTimeImmutable
    {
        $tz = new \DateTimeZone(self::UTC);

        $immutable = null;
        try {
            $immutable = new \DateTimeImmutable('now', $tz);
        } catch (\Exception $ex) {
            // Could never happen.
        }

        return $immutable;
    }

    /**
     * @param string $start
     * @param string $end
     *
     * @return \Generator
     * @throws DateAssertion
     * @throws \Exception
     */
    public function rangeFromString(string $start, string $end): \Generator
    {
        DateAssertion::invalidRange($start, $end);

        return $this->iter(new self($start), new self($end));
    }

    /**
     * @param DateTime $start
     * @param DateTime $end
     *
     * @return \Generator
     * @throws DateAssertion
     */
    public function range(DateTime $start, DateTime $end): \Generator
    {
        DateAssertion::invalidRange($start, $end);

        return $this->iter($start, $end);
    }

    /**
     * @param int $minutes
     *
     * @return DateTime
     * @throws \Exception
     */
    public function addMinutes(int $minutes): DateTime
    {
        return new self((string) ($this->getTimestamp() + $minutes));
    }


    /**
     * @param DateTime $param
     * @param DateTime $param1
     *
     * @return \Generator
     */
    private function iter(DateTime $param, DateTime $param1): \Generator
    {
        for ($i = $param; $i <= $param1; $i->modify('+1 day')) {
            yield $i->format(self::W3C);
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->format(self::W3C);
    }
}
