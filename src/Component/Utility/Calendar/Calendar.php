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


/**
 * Class Calendar
 *
 * @package Vection\Component\Utility\Calendar
 *
 * @author  Bjoern Klemm <vection@bjoernklemm.de>
 */
class Calendar implements CalendarInterface
{

    /** @var \DateTimeInterface */
    protected $dateTime;

    /**
     * @var array
     */
    protected $fields = [];


    public const DAY_OF_MONTH  = 0;
    public const DAYS_OF_MONTH = 1;
    public const DAY_OF_WEEK   = 2;
    public const DAY_OF_YEAR   = 3;
    public const WEEK_OF_YEAR  = 4;
    public const MONTH_OF_YEAR = 5;
    public const MONTH         = 6;
    public const YEAR          = 7;
    public const IS_LEAP_YEAR  = 8;
    public const AM_PM         = 9;

    /**
     * Calendar constructor.
     *
     * Creates Calendar object whose calendar fields
     * have been initialized with the current date and time:
     */
    public function __construct()
    {
        $this->dateTime = DateTime::utcNow();

        $this->set(self::DAY_OF_MONTH, $this->dateTime->format('n'));
        $this->set(self::DAYS_OF_MONTH, $this->dateTime->format('t'));
        $this->set(self::DAY_OF_WEEK, $this->dateTime->format('w'));
        $this->set(self::DAY_OF_YEAR, $this->dateTime->format('z'));
        $this->set(self::WEEK_OF_YEAR, $this->dateTime->format('W'));
        $this->set(self::MONTH_OF_YEAR, $this->dateTime->format('m'));
        $this->set(self::MONTH, $this->dateTime->format('F'));
        $this->set(self::YEAR, $this->dateTime->format('Y'));
        $this->set(self::IS_LEAP_YEAR, $this->dateTime->format('L'));
        $this->set(self::AM_PM, $this->dateTime->format('A'));
    }

    /**
     * @TODO: recompute all fields
     *
     * @param mixed $f
     * @param mixed $value
     */
    public function set($f, $value): void
    {
        $this->fields[$f] = $value;
    }

    /**
     * @param mixed $f
     *
     * @return mixed
     */
    public function get($f)
    {
        return $this->fields[$f];
    }

    /**
     * @TODO: recompute all fields
     *
     * @param mixed $f
     * @param mixed $delta
     */
    public function add($f, $delta)
    {
        $this->set($f, ($this->get($f) + $delta));
    }

    /**
     *
     */
    public function computeFields(): void
    {

    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->dateTime->format($this->dateTime::W3C);
    }
}
