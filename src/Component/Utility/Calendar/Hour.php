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
 * Class Hour
 *
 * @package Vection\Component\Utility\Calendar
 */
class Hour
{
    /** @var int hour in seconds */
    public const SECOUND_UNIT = 3600;
    /** @var int hour in minutes */
    public const MINUTE_UNIT = 60;
    /** @var int hour in industry minutes */
    public const INDUSTRY_UNIT = 100;

    public const PRECISION = 2;

    /**
     * @param int $hours
     *
     * @return float|int
     */
    public static function toSeconds(int $hours)
    {
        return $hours * self::SECOUND_UNIT;
    }

    /**
     * @param int $hours
     *
     * @return float|int
     */
    public static function toMinutes(int $hours)
    {
        return $hours * self::MINUTE_UNIT;
    }

    /**
     * @param string $hours
     * @param string $term
     *
     * @return float|int
     */
    public static function toIndustryHours(string $hours,$term = ',')
    {
        [$hour,$minute] = explode($term,$hours);
        return (int) $hour + (int) $minute / self::MINUTE_UNIT;
    }

    /**
     * @param string $hours
     * @param string $term
     *
     * @return float|int
     */
    public static function toIndustryMinutes(string $hours, $term = ',')
    {
        return self::toIndustryHours($hours, $term) * self::INDUSTRY_UNIT;
    }

    /**
     * @param string $hours
     * @param string $term
     *
     * @return float|int
     */
    public static function toIndustrySeconds(string $hours, $term = ',')
    {
        return self::toIndustryMinutes($hours, $term) * self::INDUSTRY_UNIT;
    }

    /**
     * @param string $hours
     * @param string $term
     *
     * @return string
     */
    public static function getMinuteSegement(string $hours, $term = ','): string
    {
        $seg = explode($term,$hours);
        $val = $seg[1] ?? 0;
        return str_pad($val,self::PRECISION,'0');
    }

    /**
     * @param string $minute
     *
     * @return string
     */
    public static function minuteToHour(string $minute): string
    {
        return '0,'.$minute;
    }
}