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
     * @param $hours
     *
     * @return float|int
     */
    public static function toSeconds($hours)
    {
        return $hours * self::SECOUND_UNIT;
    }

    /**
     * @param $hours
     *
     * @return float|int
     */
    public static function toMinutes($hours)
    {
        return $hours * self::MINUTE_UNIT;
    }

    /**
     * @param        $hours
     * @param string $term
     *
     * @return float|int
     */
    public static function toIndustryHours($hours,$term = ',')
    {
        [$hour,$minute] = explode($term,$hours);
        return $hour + $minute / self::MINUTE_UNIT;
    }

    /**
     * @param        $hours
     * @param string $term
     *
     * @return float|int
     */
    public static function toIndustryMinutes($hours, $term = ',')
    {
        return self::toIndustryHours($hours, $term) * self::INDUSTRY_UNIT;
    }

    /**
     * @param        $hours
     * @param string $term
     *
     * @return float|int
     */
    public static function toIndustrySeconds($hours, $term = ',')
    {
        return self::toIndustryMinutes($hours, $term) * self::INDUSTRY_UNIT;
    }

    /**
     * @param        $hours
     * @param string $term
     *
     * @return int
     */
    public static function getMinuteSegement($hours, $term = ','): int
    {
        $seg = explode($term,$hours);
        $val = $seg[1] ?? 0;
        return str_pad($val,self::PRECISION,'0');
    }

    /**
     * @param $minute
     *
     * @return string
     */
    public static function minuteToHour($minute)
    {
        return '0,'.$minute;
    }
}