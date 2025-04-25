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
use InvalidArgumentException;
use RuntimeException;

/**
 * Class Crypto
 *
 * @package Vection\Component\Common
 * @author  David Lung <david.lung@appsdock.de>
 */
class Crypto
{
    public const string CIPHER_AES_128_CBC       = 'aes-128-cbc';
    public const string CIPHER_AES_128_GCM       = 'aes-128-gcm';
    public const string CIPHER_AES_192_CBC       = 'aes-192-cbc';
    public const string CIPHER_AES_192_GCM       = 'aes-192-gcm';
    public const string CIPHER_AES_256_CBC       = 'aes-256-cbc';
    public const string CIPHER_AES_256_GCM       = 'aes-256-gcm';
    public const string CIPHER_BLOWFISH          = 'blowfish';
    public const string CIPHER_CAMELLIA_128_CBC  = 'camellia-128-cbc';
    public const string CIPHER_CAMELLIA_192_CBC  = 'camellia-192-cbc';
    public const string CIPHER_CAMELLIA_256_CBC  = 'camellia-256-cbc';
    public const string CIPHER_CHACHA20_POLY1305 = 'chacha20-poly1305';

    public const array CIPHERS = [
        self::CIPHER_AES_128_CBC,
        self::CIPHER_AES_192_CBC,
        self::CIPHER_AES_128_GCM,
        self::CIPHER_AES_192_GCM,
        self::CIPHER_AES_256_CBC,
        self::CIPHER_AES_256_GCM,
        self::CIPHER_BLOWFISH,
        self::CIPHER_CAMELLIA_128_CBC,
        self::CIPHER_CAMELLIA_192_CBC,
        self::CIPHER_CAMELLIA_256_CBC,
        self::CIPHER_CHACHA20_POLY1305,
    ];

    public const string HASH_CRC32     = 'crc32';
    public const string HASH_MD5       = 'md5';
    public const string HASH_MURMUR3A  = 'murmur3a';
    public const string HASH_MURMUR3C  = 'murmur3c';
    public const string HASH_MURMUR3F  = 'murmur3f';
    public const string HASH_SHA1      = 'sha1';
    public const string HASH_SHA256    = 'sha256';
    public const string HASH_SHA512    = 'sha512';
    public const string HASH_WHIRLPOOL = 'whirlpool';
    public const string HASH_XXH3      = 'xxh3';
    public const string HASH_XXH32     = 'xxh32';
    public const string HASH_XXH64     = 'xxh64';
    public const string HASH_XXH128    = 'xxh128';

    public const array HASHES = [
        self::HASH_CRC32,
        self::HASH_MD5,
        self::HASH_MURMUR3A,
        self::HASH_MURMUR3C,
        self::HASH_MURMUR3F,
        self::HASH_SHA1,
        self::HASH_SHA256,
        self::HASH_SHA512,
        self::HASH_WHIRLPOOL,
        self::HASH_XXH3,
        self::HASH_XXH32,
        self::HASH_XXH64,
        self::HASH_XXH128,
    ];

    /**
     * Generates new random identity string.
     *
     * @param string $prefix
     * @param int    $length
     *
     * @return string
     */
    public static function identity(string $prefix = '', int $length = 8): string
    {
        if ($length <= 0) {
            throw new InvalidArgumentException('Length must be greater than 0');
        }

        $identity = substr(
            preg_replace('/[^a-zA-Z\d]/', '', base64_encode(self::bytes($length + 4))),
            0,
            $length
        );

        return strlen($identity) === $length ? $prefix.$identity : self::identity($prefix, $length);
    }

    /**
     * Generates new random UUID v4 string.
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
     * @param string  $password
     * @param string  $algo
     * @param mixed[] $options
     *
     * @return string
     */
    public static function createPasswordHash(string $password, string $algo = PASSWORD_BCRYPT, array $options = []): string
    {
        return password_hash($password, $algo, $options);
    }

    /**
     * Verifies password by hash calculated via createPasswordHash method before.
     *
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
     * Generates new random hash or hashes given value.
     *
     * @param string      $algo
     * @param string|null $data
     * @param bool        $binary
     *
     * @return string
     */
    public static function hash(string $algo = self::HASH_SHA1, string|null $data = null, bool $binary = false): string
    {
        $data = $data ?? self::identity('', 16);

        return hash($algo, $data, $binary);
    }

    /**
     * Hashes given file.
     *
     * @param string      $path
     * @param string|null $algo
     * @param bool        $binary
     *
     * @return string
     */
    public static function hashFile(string $path, string|null $algo = self::HASH_SHA1, bool $binary = false): string
    {
        return hash_file($algo, $path, $binary);
    }

    /**
     * Generates new random bytes string.
     *
     * @note The number of characters generated is more than the given length.
     *
     * @param int $length
     *
     * @return string
     */
    public static function bytes(int $length): string
    {
        try {
            return random_bytes($length);
        }
        catch (Exception $e) {
            throw new RuntimeException('Unable to generate random bytes via random_bytes.', previous: $e);
        }
    }

    /**
     * Generates new random hexadecimal string.
     *
     * @param int $length
     *
     * @return string
     */
    public static function hex(int $length = 64): string
    {
        $hex = bin2hex(substr(self::bytes((int) ceil($length / 2)),0, $length));

        return strlen($hex) === $length ? $hex : self::hex($length);
    }

    /**
     * Encrypts content via public key.
     *
     * @note Only RSA public keys are supported by OpenSSL!
     *
     * @see https://www.php.net/manual/de/function.openssl-public-encrypt.php For padding constants.
     *
     * @param string $content
     * @param string $publicKey The public key as string or the path to the public key file.
     * @param int    $padding
     *
     * @return string
     */
    public static function encryptViaPublicKey(string $content, string $publicKey, int $padding = 1): string
    {
        if (!extension_loaded('openssl')) {
            throw new RuntimeException('Encryption via public key requires the OpenSSL extension.');
        }

        if (is_file($publicKey)) {
            $publicKey = file_get_contents($publicKey);
        }

        if (!$publicKey) {
            throw new RuntimeException('The public key is empty or missing.');
        }

        if (!@openssl_public_encrypt($content, $encrypted, $publicKey, $padding)) {
            throw new RuntimeException('An error occurred during encryption.'.(($e = openssl_error_string()) ? " $e" : ''));
        }

        return $encrypted;
    }

    /**
     * Decrypts content via private key.
     *
     * @note Only RSA private keys are supported by OpenSSL!
     *
     * @see https://www.php.net/manual/de/function.openssl-public-encrypt.php For padding constants.
     *
     * @param string $encryptedContent
     * @param string $privateKey       The private key as string or the path to the private key file.
     * @param int    $padding
     *
     * @return string
     */
    public static function decryptViaPrivateKey(
        string $encryptedContent,
        string $privateKey,
        int    $padding = 1
    ): string
    {
        if (!extension_loaded('openssl')) {
            throw new RuntimeException('Decryption via private key requires the OpenSSL extension.');
        }

        if (is_file($privateKey)) {
            $privateKey = file_get_contents($privateKey);
        }

        if (!$privateKey) {
            throw new RuntimeException('The private key is empty or missing.');
        }

        if (!@openssl_private_decrypt($encryptedContent, $decrypted, $privateKey, $padding)) {
            throw new RuntimeException('An error occurred during decryption.'.(($e = openssl_error_string()) ? " $e" : ''));
        }

        return $decrypted;
    }

    /**
     * Encrypt content via passphrase and secret key.
     *
     * @param string $content
     * @param string $passphrase
     * @param string $secretKey
     * @param string $cipher
     * @param string $hashAlgo
     *
     * @return string
     */
    public static function encrypt(
        string $content,
        string $passphrase,
        string $secretKey,
        string $cipher   = self::CIPHER_AES_256_GCM,
        string $hashAlgo = self::HASH_SHA256
    ): string
    {
        if (!extension_loaded('openssl')) {
            throw new RuntimeException('OpenSSLEncryption requires the ext-openssl extension.');
        }

        if (!in_array($cipher, openssl_get_cipher_methods(), true)) {
            throw new RuntimeException("OpenSSL: Invalid cipher method '$cipher'.");
        }

        $ivLen = openssl_cipher_iv_length($cipher);

        if ($ivLen === false) {
            throw new RuntimeException('Unable to get the cipher iv length via openSSL.');
        }

        $iv = self::bytes($ivLen);

        $encrypted  = openssl_encrypt($content, $cipher, $passphrase, 0, $iv, $tag, '', 4);
        $hash       = hash_hmac($hashAlgo, $encrypted, $secretKey);
        $hashLength = strlen($hash);

        return str_rot13(base64_encode($hashLength.'||'.$iv.$hash.$tag.$encrypted));
    }

    /**
     * Decrypt content via passphrase and secret key.
     *
     * @param string $content
     * @param string $passphrase
     * @param string $secretKey
     * @param string $cipher
     * @param string $hashAlgo
     *
     * @return string|null
     */
    public static function decrypt(
        string $content,
        string $passphrase,
        string $secretKey,
        string $cipher   = self::CIPHER_AES_256_GCM,
        string $hashAlgo = self::HASH_SHA256
    ): string|null
    {

        if (!extension_loaded('openssl')) {
            throw new RuntimeException('OpenSSLEncryption requires the ext-openssl extension.');
        }

        if (!in_array($cipher, openssl_get_cipher_methods(), true)) {
            throw new RuntimeException("OpenSSL: Invalid cipher method '$cipher'.");
        }

        $ivLen = openssl_cipher_iv_length($cipher);

        if ($ivLen === false) {
            throw new RuntimeException('Unable to get the cipher iv length via openSSL.');
        }

        $info = base64_decode(str_rot13($content));

        if ($info === false || !str_contains($info, '||')) {
            return null;
        }

        [$hashLength, $info] = explode('||', $info);
        $hashLength          = (int) $hashLength;
        $iv        = substr($info,0, $ivLen);
        $hash      = substr($info, $ivLen, $hashLength);
        $tag       = substr($info, ($ivLen + $hashLength), 4);
        $encrypted = substr($info, ($ivLen + $hashLength + 4));

        $decrypted = openssl_decrypt($encrypted, $cipher, $passphrase, 0, $iv, $tag);

        if ($decrypted === false) {
            throw new RuntimeException(
                'Error while using openSSL decryption. My caused by inconsistent parameters.'
            );
        }

        $hashCheck = hash_hmac($hashAlgo, $encrypted, $secretKey);

        return hash_equals($hash, $hashCheck) ? $decrypted : null;
    }
}
