<?php

/*
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Common;

use Exception;
use RuntimeException;

/**
 * Class Crypto
 *
 * @package Vection\Component\Common
 * @author  David Lung <david.lung@appsdock.de>
 */
class Crypto
{
    public const HASH_CRC32     = 'crc32';
    public const HASH_MD5       = 'md5';
    public const HASH_SHA1      = 'sha1';
    public const HASH_SHA256    = 'sha256';
    public const HASH_SHA512    = 'sha512';
    public const HASH_WHIRLPOOL = 'whirlpool';

    /**
     * Generates a new identity string.
     *
     * @param string $prefix
     * @param int    $length
     *
     * @return string
     */
    public static function identity(string $prefix = '', int $length = 7): string
    {
        return $prefix.substr(
            preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(self::bytes($length))), 0, $length
        );
    }

    /**
     * Generates a new random UUID v4 string
     *
     * @return string
     */
    public static function uuid4(): string
    {
        $hex = self::hex(16);

        $version = 4;

        $timeHi  = substr($hex, 12, 4);
        $timeHi  = (hexdec($timeHi) & 0x0fff);
        $timeHi &= ~(0xf000);
        $timeHi |= ($version << 12);

        $clockSeqHi  = hexdec(substr($hex, 16, 2));
        $clockSeqHi &= 0x3f;
        $clockSeqHi &= ~(0xc0);
        $clockSeqHi |= 0x80;

        $fields = [
            'time_low' => substr($hex, 0, 8),
            'time_mid' => substr($hex, 8, 4),
            'time_hi_and_version' => str_pad(dechex($timeHi), 4, '0', STR_PAD_LEFT),
            'clock_seq_hi_and_reserved' => str_pad(dechex($clockSeqHi),2,'0',STR_PAD_LEFT),
            'clock_seq_low' => substr($hex, 18, 2),
            'node' => substr($hex, 20, 12),
        ];

        return vsprintf(
            '%08s-%04s-%04s-%02s%02s-%012s',
            $fields
        );
    }

    /**
     * @param string $password
     * @param string $algo
     * @param array  $options
     *
     * @return string
     */
    public static function createPasswordHash(string $password, $algo = PASSWORD_BCRYPT, array $options = []): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Generates and returns a new random hash string or hashes a given value.
     *
     * @param string      $algo
     * @param string|null $string
     * @param bool        $binary
     *
     * @return string
     */
    public static function hash(
        string $algo = self::HASH_SHA1, ?string $data = null, $binary = false): string
    {
        $data = $data ?? self::identity('', 16);

        switch ($algo) {
            case self::HASH_CRC32:     return hash(self::HASH_CRC32, $data, $binary);
            case self::HASH_MD5:       return md5($data, $binary);
            case self::HASH_SHA256:    return hash(self::HASH_SHA256, $data, $binary);
            case self::HASH_SHA512:    return hash(self::HASH_SHA512, $data, $binary);
            case self::HASH_WHIRLPOOL: return hash(self::HASH_WHIRLPOOL, $data, $binary);
            case self::HASH_SHA1:
            default: return sha1($data, $binary);
        }
    }

    /**
     * Generates a new random bytes string
     *
     * @param int $length
     *
     * @return string
     */
    public static function bytes(int $length = 32): string
    {
        static $retries = 0;

        try {
            $bytes = random_bytes($length);

            $retries = 0;
        }
        catch (Exception $e) {
            if ($retries >= 10) {
                throw new RuntimeException('Unable to generate random bytes', $e->getCode(), $e);
            }

            $retries++;

            usleep($retries * 1000);

            return self::bytes($length);
        }

        return $bytes;
    }

    /**
     * Generates a new random hexadecimal string
     *
     * @param int $length
     *
     * @return string
     */
    public static function hex(int $length = 64): string
    {
        return bin2hex(substr(self::bytes((int) ceil($length / 2)),0, $length));
    }

    /**
     * @param string $content
     * @param string $keyOrFilePath
     * @param int $padding One of
     *                     OPENSSL_PKCS1_PADDING,
     *                     OPENSSL_SSLV23_PADDING,
     *                     OPENSSL_PKCS1_OAEP_PADDING,
     *                     OPENSSL_NO_PADDING.
     *
     * @return string
     */
    public static function publicKeyEncrypt(string $content, string $keyOrFilePath, int $padding = 1): string
    {
        if ( ! extension_loaded('openssl') ) {
            throw new RuntimeException('OpenSSLEncryption requires the ext-openssl extension.');
        }

        $encryptedContent = null;

        if (strpos($keyOrFilePath, '-----') !== 0 && file_exists($keyOrFilePath)) {
            $keyOrFilePath = file_get_contents($keyOrFilePath);
        }

        openssl_public_encrypt($content, $encryptedContent, $keyOrFilePath, $padding);

        return $encryptedContent;
    }

    /**
     * @param string $encryptedContent
     * @param string $keyOrFilePath
     * @param int    $padding One of
     *                          OPENSSL_PKCS1_PADDING,
     *                          OPENSSL_SSLV23_PADDING,
     *                          OPENSSL_PKCS1_OAEP_PADDING,
     *                          OPENSSL_NO_PADDING.
     *
     * @return string
     */
    public static function privateKeyDecrypt(string $encryptedContent, string $keyOrFilePath, int $padding = 1): string
    {
        if ( ! extension_loaded('openssl') ) {
            throw new RuntimeException('OpenSSLEncryption requires the ext-openssl extension.');
        }

        $decrypted = null;

        if (strpos($keyOrFilePath, '-----') !== 0 && file_exists($keyOrFilePath)) {
            $keyOrFilePath = file_get_contents($keyOrFilePath);
        }

        openssl_private_decrypt($encryptedContent, $decrypted, $keyOrFilePath, $padding);

        return $decrypted;
    }

    /**
     * @param string $content
     * @param string $key
     * @param string $secret
     * @param string $cipherAlgo
     * @param string $algo
     *
     * @return string
     */
    public static function encrypt(
        string $content,
        string $key,
        string $secret,
        string $cipherAlgo = 'aes-256-gcm',
        string $algo = self::HASH_SHA256
    ): string
    {
        if ( ! extension_loaded('openssl') ) {
            throw new RuntimeException('OpenSSLEncryption requires the ext-openssl extension.');
        }

        if ( ! in_array($cipherAlgo, openssl_get_cipher_methods(), true) ) {
            throw new RuntimeException("OpenSSL: Invalid cipher method '{$cipherAlgo}'.");
        }

        $ivLen = openssl_cipher_iv_length($cipherAlgo);

        if ( $ivLen === false ) {
            throw new RuntimeException('Unable to get the cipher iv length via openSSL.');
        }

        $iv = self::bytes($ivLen);

        $encrypted  = openssl_encrypt($content, $cipherAlgo, $key, 0, $iv, $tag, '', 4);
        $hash       = hash_hmac($algo, $encrypted, $secret);
        $hashLength = strlen($hash);

        return str_rot13(base64_encode($hashLength.'||'.$iv.$hash.$tag.$encrypted));
    }

    /**
     * @param string $encryptedContent
     * @param string $key
     * @param string $secret
     * @param string $cipherAlgo
     * @param string $algo
     *
     * @return string|null
     */
    public static function decrypt(
        string $encryptedContent,
        string $key,
        string $secret,
        string $cipherAlgo = 'aes-256-gcm',
        string $algo = self::HASH_SHA256
    ): ? string
    {

        if ( ! extension_loaded('openssl') ) {
            throw new RuntimeException('OpenSSLEncryption requires the ext-openssl extension.');
        }

        if ( ! in_array($cipherAlgo, openssl_get_cipher_methods(), true) ) {
            throw new RuntimeException("OpenSSL: Invalid cipher method '{$cipherAlgo}'.");
        }

        $ivLen = openssl_cipher_iv_length($cipherAlgo);

        if ( $ivLen === false ) {
            throw new RuntimeException('Unable to get the cipher iv length via openSSL.');
        }

        $info = base64_decode(str_rot13($encryptedContent));

        if ($info === false || strpos($info, '||') === false) {
            return null;
        }

        [$hashLength, $info] = explode('||', $info);
        $hashLength          = (int) $hashLength;
        $iv        = substr($info,0, $ivLen);
        $hash      = substr($info, $ivLen, $hashLength);
        $tag       = substr($info, ($ivLen + $hashLength), 4);
        $encrypted = substr($info, ($ivLen + $hashLength + 4));

        $decrypted = openssl_decrypt($encrypted, $cipherAlgo, $key, 0, $iv, $tag);

        if ( $decrypted === false ) {
            throw new RuntimeException(
                'Error while using openSSL decryption. My caused by inconsistent parameters.'
            );
        }

        $hashCheck = hash_hmac($algo, $encrypted, $secret);

        return hash_equals($hash, $hashCheck) ? $decrypted : null;
    }
}
