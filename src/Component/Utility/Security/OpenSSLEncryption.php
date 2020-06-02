<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Vection\Component\Utility\Security;

use RuntimeException;

/**
 * Class OpenSSLEncryption
 *
 * @package Vection\Component\Utility\Security
 *
 * @author David Lung <vection@davidlung.de>
 */
class OpenSSLEncryption
{

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $hashAlgorithm;

    /**
     * OpenSSLEncryption constructor.
     *
     * @param string $method
     * @param string $hashAlgorithm
     */
    public function __construct(string $method = 'aes-256-gcm', string $hashAlgorithm = 'sha256')
    {
        if ( ! extension_loaded('openssl') ) {
            throw new RuntimeException('OpenSSLEncryption requires the ext-openssl extension.');
        }

        $this->method        = $method;
        $this->hashAlgorithm = $hashAlgorithm;

        if ( ! in_array($method, openssl_get_cipher_methods(), true) ) {
            throw new RuntimeException("OpenSSL: Invalid cipher method '{$method}'.");
        }
    }

    /**
     * @param string $content
     * @param string $key
     * @param int    $padding
     *
     * @return string
     */
    public function publicEncrypt(string $content, string $key, int $padding = OPENSSL_PKCS1_PADDING): string
    {
        $crypted = null;

        if (strpos($key, '-----') !== 0 && file_exists($key)) {
            $key = file_get_contents($key);
        }

        openssl_public_encrypt($content, $crypted, $key, $padding);

        return $crypted;
    }

    /**
     * @param string $crypted
     * @param string $key
     * @param int    $padding
     *
     * @return string
     */
    public function privateDecrypt(string $crypted, string $key, int $padding = OPENSSL_PKCS1_PADDING): string
    {
        $decrypted = null;

        if (strpos($key, '-----') !== 0 && file_exists($key)) {
            $key = file_get_contents($key);
        }

        openssl_private_decrypt($crypted, $decrypted, $key, $padding);

        return $decrypted;
    }

    /**
     * @param string $content
     * @param string $key
     * @param string $secret
     *
     * @return string
     */
    public function encrypt(string $content, string $key, string $secret): string
    {
        $ivLen = openssl_cipher_iv_length($this->method);

        if ( $ivLen === false ) {
            throw new RuntimeException('Unable to get the cipher iv length via openSSL.');
        }

        $iv = openssl_random_pseudo_bytes($ivLen, $isSourceStrong);

        if ( $iv === false || $isSourceStrong === false ) {
            throw new RuntimeException('Unable to create the cipher iv via openSSL (not secure random bytes).');
        }

        $encrypted  = openssl_encrypt($content, $this->method, $key, 0, $iv, $tag, '', 4);
        $hash       = hash_hmac($this->hashAlgorithm, $encrypted, $secret);
        $hashLength = strlen($hash);

        return str_rot13(base64_encode($hashLength.'||'.$iv.$hash.$tag.$encrypted));
    }

    /**
     * @param string $content
     * @param string $key
     * @param string $secret
     *
     * @return string|null
     */
    public function decrypt(string $content, string $key, string $secret): ? string
    {
        $ivLen = openssl_cipher_iv_length($this->method);

        if ( $ivLen === false ) {
            throw new RuntimeException('Unable to get the cipher iv length via openSSL.');
        }

        $info = base64_decode(str_rot13($content));
        [$hashLength, $info] = explode('||', $info);
        $hashLength          = (int) $hashLength;
        $iv        = substr($info,0, $ivLen);
        $hash      = substr($info, $ivLen, $hashLength);
        $tag       = substr($info, ($ivLen + $hashLength), 4);
        $encrypted = substr($info, ($ivLen + $hashLength + 4));

        $decrypted = openssl_decrypt($encrypted, $this->method, $key, 0, $iv, $tag);

        if ( $decrypted === false ) {
            throw new RuntimeException(
                'Error while using openSSL decryption. My caused by inconsistent parameters.'
            );
        }

        $hashCheck = hash_hmac($this->hashAlgorithm, $encrypted, $secret);

        return hash_equals($hash, $hashCheck) ? $decrypted : null;
    }
}
