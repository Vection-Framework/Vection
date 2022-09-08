<?php
/*
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Validator;

use Vection\Component\Validator\Validator;
use Vection\Component\Validator\Validator\Exception\IllegalTypeException;

/**
 * Class Iban
 *
 * @package Vection\Component\Validator\Validator
 * @author  Bjoern Klemm <bjoern.klemm@appsdock.de>
 */
class Iban extends Validator
{
    /**
     * ISO Country Codes to IBAN length mapping.
     */
    public const ISO_CODES = [
        'AL' => 28,
        'AD' => 24,
        'AE' => 23,
        'AT' => 20,
        'AZ' => 28,
        'BH' => 22,
        'BE' => 16,
        'BA' => 20,
        'BR' => 29,
        'BG' => 22,
        'CH' => 21,
        'CR' => 21,
        'CY' => 28,
        'CZ' => 24,
        'DE' => 22,
        'DK' => 18,
        'DO' => 28,
        'EE' => 20,
        'ES' => 24,
        'FO' => 18,
        'FI' => 18,
        'FR' => 27,
        'GB' => 22,
        'GE' => 22,
        'GI' => 23,
        'GR' => 27,
        'GL' => 18,
        'GT' => 28,
        'HR' => 21,
        'HU' => 28,
        'IS' => 26,
        'IE' => 22,
        'IL' => 23,
        'IT' => 27,
        'JO' => 30,
        'KZ' => 20,
        'KW' => 30,
        'LV' => 21,
        'LB' => 28,
        'LI' => 21,
        'LT' => 20,
        'LU' => 20,
        'MK' => 19,
        'MT' => 31,
        'MR' => 27,
        'MU' => 30,
        'MC' => 27,
        'MD' => 24,
        'ME' => 22,
        'NL' => 18,
        'NO' => 15,
        'PK' => 24,
        'PS' => 29,
        'PL' => 28,
        'PT' => 25,
        'QA' => 29,
        'RO' => 24,
        'SM' => 27,
        'SA' => 24,
        'RS' => 22,
        'SK' => 24,
        'SI' => 19,
        'SE' => 24,
        'TN' => 24,
        'TR' => 26,
        'VG' => 24,
    ];

    /**
     * Letter to digit mapping to calculate the total IBAN.
     */
    public const LETTERS = [
        'A' => 10,
        'B' => 11,
        'C' => 12,
        'D' => 13,
        'E' => 14,
        'F' => 15,
        'G' => 16,
        'H' => 17,
        'I' => 18,
        'J' => 19,
        'K' => 20,
        'L' => 21,
        'M' => 22,
        'N' => 23,
        'O' => 24,
        'P' => 25,
        'Q' => 26,
        'R' => 27,
        'S' => 28,
        'T' => 29,
        'U' => 30,
        'V' => 31,
        'W' => 32,
        'X' => 33,
        'Y' => 34,
        'Z' => 35,
    ];

    /**
     * @inheritDoc
     */
    protected function onValidate(mixed $value): bool
    {
        if (!is_string($value)) {
            throw new IllegalTypeException(
                sprintf('The value must be of type "string", but type "%s" was passed.', gettype($value))
            );
        }

        $iban    = $this->normalize($value);
        $isoCode = substr($value,0, 2);

        if (!array_key_exists($isoCode, self::ISO_CODES)) {
            return false;
        }

        if (strlen($iban) !== self::ISO_CODES[$isoCode]) {
            return false;
        }

        return $this->compute($this->replace($this->rearrange($iban))) === 1;
    }

    /**
     * Normalizes the IBAN by replacing spaces and transform into upper letters.
     *
     * @param string $iban
     *
     * @return string
     */
    protected function normalize(string $iban): string
    {
        return strtoupper(str_replace(' ','', $iban));
    }

    /**
     * Rearranges the IBAN by moving the first 4 chars to the end.
     *
     * @see https://en.wikipedia.org/wiki/International_Bank_Account_Number#Validating_the_IBAN
     *
     * @param string $iban
     *
     * @return string
     */
    protected function rearrange(string $iban): string
    {
        return substr($iban,4).substr($iban,0, 4);
    }

    /**
     * Replaces the letters with the associated digits.
     *
     * @see https://en.wikipedia.org/wiki/International_Bank_Account_Number#Validating_the_IBAN
     *
     * @param string $iban
     *
     * @return string
     */
    protected function replace(string $iban): string
    {
        $chars = str_split($iban);

        foreach ($chars as $idx => $value) {
            $char = $value;
            if (!is_numeric($char) ) {
                $chars[$idx] = self::LETTERS[$char];
            }
        }

        return implode('', $chars);
    }

    /**
     * Computes the remainder of the IBAN.
     *
     * @see https://en.wikipedia.org/wiki/International_Bank_Account_Number#Validating_the_IBAN
     *
     * @param string $iban
     *
     * @return int
     */
    protected function compute(string $iban): int
    {
        $mod = '';
        do {
            $n    = (int) ($mod.substr($iban,0,5));
            $iban = substr($iban, 5);
            $mod  = ($n % 97);
        } while ($iban !== '');

        return $mod;
    }

    /**
     * Returns an message which will be display when the validation
     * failed. The message can contain the constraint names in curly brackets
     * that will be replaced with the values from constraints get by getConstraints method.
     *
     * E.g. "Value {value} does not match the given format {format}.
     *
     * By default, you can use the {value} token to place the current value.
     *
     * @return string
     */
    protected function getMessage(): string
    {
        return 'Value "{value}" is not an iban';
    }
}
