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
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Bjoern Klemm <vection@bjoernklemm.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Utility\Lang;

/**
 * Class StringType
 *
 * Encoding save string operations
 * for multi byte strings
 *
 * @package Vection\Component\Utility\Lang
 *
 * @author  Bjoern Klemm <vection@bjoernklemm.de>
 *
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class StringType
{

    /** @var string */
    private $str;

    /** @var string */
    private $encoding;

    /**
     * StringType constructor.
     *
     * @param string $str      multi byte string
     * @param string $encoding encoding type for multi bytes
     */
    public function __construct(?string $str = null, ?string $encoding = null)
    {
        $this->str      = ($str ?? '');
        $this->encoding = ($encoding ?? 'UTF-8');
    }

    /**
     * @param string $str string to concat
     *
     * @return StringType
     */
    public function append(string $str): StringType
    {
        $this->str .= $str;

        return $this;
    }

    /**
     * @param string $str string to concat
     *
     * @return string
     */
    public function concat(string $str): string
    {
        return $this->str . $str;
    }

    /**
     * @param string $str
     *
     * @return StringType
     */
    public function prepend(string $str): StringType
    {
        $this->str = $str . $this->str;

        return $this;
    }

    /**
     * @return StringType
     */
    public function title(): StringType
    {
        $this->str = mb_convert_case($this->str, MB_CASE_TITLE, $this->encoding);

        return $this;
    }

    /**
     * @param string $needle
     * @param string $replace
     *
     * @return StringType
     */
    public function replace(string $needle, string $replace): StringType
    {
        $this->str = str_replace($needle, $replace, $this->str);

        return $this;
    }

    /**
     * @param int $multiplier
     *
     * @return StringType
     */
    public function repeat(int $multiplier): StringType
    {
        $this->str = str_repeat($this->str, $multiplier);

        return $this;
    }

    /**
     * @param string $needle
     *
     * @return bool
     */
    public function startsWith(string $needle): bool
    {
        return mb_strpos($this->str, $needle, 0, $this->encoding) === false;
    }

    /**
     * @param string $needle
     *
     * @return bool
     */
    public function endsWith(string $needle): bool
    {
        return mb_substr($this->str, -\strlen($needle), null, $this->encoding) === $needle;
    }

    /**
     * @param string $needle
     * @param int    $offset
     *
     * @return bool
     */
    public function contains(string $needle, ?int $offset = null): bool
    {
        return mb_strpos($this->str, $needle, ($offset ?? 0), $this->encoding) !== false;
    }

    /**
     * @param string $needle
     * @param int    $offset
     *
     * @return bool
     */
    public function notContains(string $needle, ?int $offset = null): bool
    {
        return !$this->contains($needle, ($offset ?? 0));
    }

    /**
     * @param array $array
     *
     * @return StringType
     */
    public function join(array $array): StringType
    {
        foreach ( $array as $key ) {
            $this->append($key);
        }
        return $this;
    }

    /**
     * @param int      $start
     * @param int|null $end
     *
     * @return StringType
     */
    public function limit(int $start, ?int $end = null): StringType
    {
        $this->str = mb_substr($this->str, $start, $end, $this->encoding);

        return $this;
    }

    /**
     * Find the first substring in the string on the left side
     *
     * @param string $needle
     * @param int    $offset
     *
     * @return int
     */
    public function pos(string $needle, ?int $offset = null): int
    {
        return mb_strpos($this->str, $needle, ($offset ?? 0), $this->encoding);
    }

    /**
     * Find the first substring in the string on the right side
     *
     * @param string $needle
     * @param int    $offset
     *
     * @return int
     */
    public function rpos(string $needle, ?int $offset = null): int
    {
        $this->str = $this->reversed();
        return $this->pos($needle, ($offset ?? 0));
    }

    /**
     * @return string
     */
    public function reversed(): string
    {
        $len = mb_strlen($this->str, $this->encoding);
        $tmp = '';
        while ( $len-- > 0 ) {
            $tmp .= mb_substr($this->str, $len, 1, $this->encoding);
        }
        return $tmp;
    }

    /**
     * @return string
     */
    public function upper(): string
    {
        return mb_convert_case($this->str, MB_CASE_UPPER, $this->encoding);
    }

    /**
     * @return string
     */
    public function lower(): string
    {
        return mb_convert_case($this->str, MB_CASE_LOWER, $this->encoding);
    }

    /**
     * @param string $separator
     *
     * @return array
     */
    public function split(?string $separator = null): array
    {
        return explode(($separator ?? ' '), $this->str);
    }

    /**
     * @return int
     */
    public function ord(): int
    {
        return mb_ord($this->str, $this->encoding);
    }

    /**
     * @param string $needle
     *
     * @return int
     */
    public function count(string $needle): int
    {
        return mb_substr_count($this->str, $needle, $this->encoding);
    }

    /**
     * @return int
     */
    public function len(): int
    {
        return mb_strlen($this->str, $this->encoding);
    }

    /**
     * @return string
     */
    public function capitalize(): string
    {
        $tmp = mb_strtoupper(mb_substr($this->str, 0, 1, $this->encoding), $this->encoding);
        return $tmp . mb_strtolower(mb_substr($this->str, 1, null, $this->encoding), $this->encoding);
    }

    /**
     * @param string $str
     *
     * @return bool
     */
    public function equals(string $str): bool
    {
        return $this->str === $str;
    }

    /**
     * @param string $separator
     *
     * @return string
     */
    public function camelize(string $separator = '_'): string
    {
        return str_replace($separator, '', ucwords($this->str, $separator));
    }

    /**
     * @return string
     */
    public function snake(): string
    {
        return mb_strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->str), $this->encoding);
    }

    /**
     * @param string      $toEncoding
     * @param string|null $fromEncoding
     *
     * @return string
     */
    public function encode(string $toEncoding, ?string $fromEncoding = null): string
    {
        return mb_convert_encoding($this->str, $toEncoding, $fromEncoding);
    }

    /**
     * @param int $length
     *
     * @return string
     * @throws \Exception
     */
    public function random(int $length): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $token = '';

        for ( $i = 0; $i < $length; $i++ ) {
            $chars  = \str_shuffle($chars);
            $token .= $chars[\random_int(0, (\strlen($chars) - 1))];
        }

        return $token;
    }

    // region BOOLEANS

    /**
     * @return bool
     */
    public function isUpper(): bool
    {
        return $this->str === $this->upper();
    }

    /**
     * @return bool
     */
    public function isLower(): bool
    {
        return $this->str === $this->lower();
    }

    /**
     * @return bool
     */
    public function isNumeric(): bool
    {
        return is_numeric($this->str);
    }

    /**
     * @return bool
     */
    public function isAlpha(): bool
    {
        return ctype_alpha($this->str);
    }

    /**
     * @return bool
     */
    public function isDigit(): bool
    {
        return ctype_digit($this->str);
    }

    /**
     * @return bool
     */
    public function isAlphaNumeric(): bool
    {
        return ctype_alnum($this->str);
    }
    // endregion

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->str;
    }
}
