<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Bjoern Klemm <vection@bjoernklemm.de>
 *
 * For the full copyright and license information',' please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Validator;


use Vection\Component\Validator\Validator;

/**
 * Class Iban
 *
 * Validates an iban based on the algorithms described on
 * https://en.wikipedia.org/wiki/International_Bank_Account_Number#Validating_the_IBAN
 *
 * @package Vection\Component\Validator\Validator
 */
class Iban extends Validator
{
    /**
     * Iso Country Codes with their specific country iban length
     */
    public const ISO_CODES = [
        'AL' => 28,'AD' => 24,'AT' => 20,'AZ' => 28,'BH' => 22,
        'BE' => 16,'BA' => 20,'BR' => 29,'BG' => 22,'CR' => 21,
        'HR' => 21,'CY' => 28,'CZ' => 24,'DK' => 18,'DO' => 28,
        'EE' => 20,'FO' => 18,'FI' => 18,'FR' => 27,'GE' => 22,
        'DE' => 22,'GI' => 23,'GR' => 27,'GL' => 18,'GT' => 28,
        'HU' => 28,'IS' => 26,'IE' => 22,'IL' => 23,'IT' => 27,
        'JO' => 30,'KZ' => 20,'KW' => 30,'LV' => 21,'LB' => 28,
        'LI' => 21,'LT' => 20,'LU' => 20,'MK' => 19,'MT' => 31,
        'MR' => 27,'MU' => 30,'MC' => 27,'MD' => 24,'ME' => 22,
        'NL' => 18,'NO' => 15,'PK' => 24,'PS' => 29,'PL' => 28,
        'PT' => 25,'QA' => 29,'RO' => 24,'SM' => 27,'SA' => 24,
        'RS' => 22,'SK' => 24,'SI' => 19,'ES' => 24,'SE' => 24,
        'CH' => 21,'TN' => 24,'TR' => 26,'AE' => 23,'GB' => 22,
        'VG' => 24
    ];

    /**
     * Mapping letters to digits in specific country exists letters
     * in iban, in order to calculate with the total iban number you have
     * tp translate the letters to integers
     */
    public const LETTERS = [
        'A' => 10,'B' => 11,'C' => 12,'D' => 13,'E' => 14,'F' => 15,
        'G' => 16,'H' => 17,'I' => 18,'J' => 19,'K' => 20,'L' => 21,
        'M' => 22,'N' => 23,'O' => 24,'P' => 25,'Q' => 26,'R' => 27,
        'S' => 28,'T' => 29,'U' => 30,'V' => 31,'W' => 32,'X' => 33,
        'Y' => 34,'Z' => 35,
    ];

    /**
     * @param $value
     *
     * @return bool
     */
    protected function onValidate($value): bool
    {
        $iban = $this->normalize($value);
        $isoCode = substr($value,0, 2);

        if ( ! array_key_exists($isoCode, self::ISO_CODES) ){
            return false;
        }

        if (strlen($iban) !== self::ISO_CODES[$isoCode]){
            return false;
        }

        $arranged = $this->rearrange($iban);

        $replaced = $this->convertToInteger($arranged);

        return $this->compute($replaced);

    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function normalize(string $value): string
    {
        return strtoupper(str_replace(' ','',$value));
    }

    /**
     * @param string $iban
     *
     * @return string
     */
    public function rearrange(string $iban): string
    {
        return substr($iban,4).substr($iban,0, 4);
    }

    /**
     * @param string $iban
     *
     * @return string
     */
    public function convertToInteger(string $iban): string
    {
        $chars = str_split($iban);

        foreach ($chars as $idx => $value){
            $char = $chars[$idx];
            if ( ! is_numeric($char) ){
                $chars[$idx] = self::LETTERS[$char];
            }
        }

        return implode('',$chars);
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public function compute(string $value): bool
    {
        $mod = '';
        do {
            $n = $mod . substr($value,0,5);
            $value = substr($value, 5);
            $mod = $n % 97;
        } while(strlen($value));

        return $mod === 1;
    }

    /**
     * Returns an message which will be display when the validation
     * failed. The message can contains the constraint names in curly brackets
     * that will be replaced with the values from constraints get by getConstraints method.
     *
     * E.g. "Value {value} does not match the given format {format}.
     *
     * By default you can use the {value} token to place the current value.
     *
     * @return string
     */
    protected function getMessage(): string
    {
        return 'Value "{value}" is not an iban';
    }
}