<?php

namespace Vection\Component\Common\Tests;

use InvalidArgumentException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use ValueError;
use Vection\Component\Common\Crypto;

/**
 * Class CryptoTest
 *
 * @package Vection\Component\Common\Tests
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class CryptoTest extends TestCase
{
    # region Setup

    private string $privateKey = <<<key
    -----BEGIN RSA PRIVATE KEY-----
    MIIEowIBAAKCAQEApJjBfKowHdK5E9CbgqXTNyWic6zs2y7KneSBOnhRIxwaCO5C
    rXzk9+PHWrEIpRCx6diifYrgpL3x7FPJO/BZTqfIzJlVrPNiEWIsvu0qcYjyCVsG
    T6CbkamQ0llBdrnJKT5l8bJ4P2XByG3Mguu0KznRnRWpyinRWwZZTJNBSFlS1j9P
    zOYt7cbJj+ciyZI+lWATShyVdcVRwL03E3BUWnwu+1a4gOPoGf9OgEC0OVOYIf6i
    rPcVL9AppbjWeKhOyT6hJ0CSzgtLz9WccMA45R4w+TiNtOgnBD0AsfggDCcEY8fa
    CJZbZqBwcIyMp2JN+BK8azPZlYkw6lFoV7J0owIDAQABAoIBABuyaLyllrst/l7L
    N5/gb45UEuYMLz8ivI2dKfHA1UZnMCpYjXfMEGt2NNGaAK5mBMoo3g7qE6slG1R9
    NNuVMjH+Idfr5XLY8aOjNYxbNE0ukivo2UME76ivM+NxiYaE9Miv60+kjH9+jAFC
    GKvfJc4wSIl6X7vZFZWPl+8+yDl4KVLweTG7RQyAzKXN8huS+OWSZowZgPqnB2Lr
    dXHv2QY3qYZXzjT+1Ih+Y+cGAhStbHfLltcoCccqK32253pBmorAWDM6qglAZVKP
    qL78Qnv1y5DcE/2sF9CFdtqJj/XHc+8gD/QHM/YauFkx+frHJRlSzH1TxEdYBNkA
    FuUoQIECgYEA0ZrkVlUb00sEx1fZ+WBcvPRlgY/lHhfQf24LnbMMYnc/cNxU4ZQn
    N5cc9MbS3510xS5oYsxYSzZcjqj1NE7msLUZJBsArNI5fbL/TUAhaoKyLlfMml9g
    8Kx7nyq7PUkkClFd324Kd32ZIaLWDx1wv3Hl9t6OdPdQpM+X5DhQ7VECgYEAyQeA
    NMA0yy5GfEMiPeWEJKs1h3STFrOpMM3AudfdPK4zjljq5Eb3taJLRFXAcgchALNU
    ikh1Kx5orgZ1VFL1aWwDP93ZQ8TLD1Ic5RawyS3z3hO74OiD1cKCKVnqR7W/PwDF
    jg3790lZbVrMPM08rc4juQLYDemco9n7uUye9bMCgYAL1jhw26uPmhvx+fcYSyXR
    keetkme49FVU7O0BAdyALwXJJNgySQCR9hmvhQ7hi+3NONqyQaH21WISuF3oj1Ad
    yIxb6p52JAUVISejwCxi7HCNh23JhftefA8bJPmf82JyprerZ3Z12wTrzltSTTJR
    zWfUMitPVawbMpH8VGXVkQKBgElD55DSICuQwPWWzgr69A8dKtQkQ1s5vUbhJgVV
    S/dKkdWmSG1MBAl5ja7pBctJF9kWgpAnWjSNz57lEava1EBIsmJ7ayyMs2jxB0Di
    5SldCwz76jRM6YdlbWS+tWjPL1U55cYhCJyWafY16kuajSvW/iP2imF/q6v3zQs6
    hpCLAoGBAMD70VHBxmrBs1eZthMw+gZtOUSM9iHnvOBBfmEb9s+ofbzXW9QqNo0q
    aMQbSN/JYrm+dz1sNqQkJZHhg9qHR/dWnbMzHVvS2y0ryMxQ4DfrHiCPTQD9fcbe
    +mFSCyP7R4TFU86d+tqcUyBgpgIqudt2wJqTDLVetYknBaJuRn1v
    -----END RSA PRIVATE KEY-----
    key;

    private string $publicKey = <<<key
    -----BEGIN PUBLIC KEY-----
    MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApJjBfKowHdK5E9CbgqXT
    NyWic6zs2y7KneSBOnhRIxwaCO5CrXzk9+PHWrEIpRCx6diifYrgpL3x7FPJO/BZ
    TqfIzJlVrPNiEWIsvu0qcYjyCVsGT6CbkamQ0llBdrnJKT5l8bJ4P2XByG3Mguu0
    KznRnRWpyinRWwZZTJNBSFlS1j9PzOYt7cbJj+ciyZI+lWATShyVdcVRwL03E3BU
    Wnwu+1a4gOPoGf9OgEC0OVOYIf6irPcVL9AppbjWeKhOyT6hJ0CSzgtLz9WccMA4
    5R4w+TiNtOgnBD0AsfggDCcEY8faCJZbZqBwcIyMp2JN+BK8azPZlYkw6lFoV7J0
    owIDAQAB
    -----END PUBLIC KEY-----
    key;

    // This is the encrypted string "VerySecretContent" encoded with base 64, because encrypted strings are binary.
    private string $encrypted = 'kmdjxZQhI6oCimpVloy/ilN0xcOt7L55y1IhAwKLSr/RkxWwJ3b7RQtczuHVtCwsKcenTaaLwRuSPuVwm3qX3GOb0gS7aOTwL3giPLmMn8psUiit341pBUaD9NN/dH0mhZRwt/LPxZ3tUhQhnj/XS2KPR68bzlnRxuFSiNxm/qzUf+Xv6n6PU8U7RkbvHzfdzWBl5EGf1w5ouwhvNjhab8/LB5f2fU5KwQ11iKA1WBj/tsi92eE/+Bqapf2gZ9MW0/T7NUxE2i/kX49Y5pl/y5+rvkO4sKuwF7gGTx8HaV7QagxCZfB/R67w5eOVxA1sAgqXqVgAVo7zkSMUSbMazQ==';

    private vfsStreamDirectory $fs;

    public function setUp(): void
    {
        $this->fs = vfsStream::setup('fs');
    }

    # endregion

    #region Tests

    /**
     * @group crypto
     * @group cryptoIdentity
     *
     * @dataProvider provideValidIdentityValues
     */
    public function testValidIdentityValues(string $prefix, int $length): void
    {
        $identity = Crypto::identity($prefix, $length);

        self::assertEquals(strlen($prefix) + $length, strlen($identity));

        if ($prefix !== '') {
            self::assertStringStartsWith($prefix, $identity);
        }
    }

    /**
     * @group crypto
     * @group cryptoIdentity
     *
     * @dataProvider provideInvalidIdentityValues
     */
    public function testInvalidIdentityValues(string $prefix, int $length): void
    {
        $this->expectException(InvalidArgumentException::class);

        Crypto::identity($prefix, $length);
    }

    /**
     * @group crypto
     * @group cryptoUuid4
     */
    public function testValidUuid4Values(): void
    {
        self::assertMatchesRegularExpression(
            '/[a-f\d]{8}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{12}/',
            Crypto::uuid4()
        );
    }

    /**
     * @group crypto
     * @group cryptoHash
     *
     * @dataProvider provideValidHashValues
     */
    public function testValidHashValues(string $expected, string $algo, string|null $data, bool $binary): void
    {
        self::assertEquals($expected, Crypto::hash($algo, $data, $binary));
    }

    /**
     * @group crypto
     * @group cryptoHash
     *
     * @dataProvider provideInvalidHashValues
     */
    public function testInvalidHashValues(string $algo, string|null $data, bool $binary): void
    {
        $this->expectException(ValueError::class);

        Crypto::hash($algo, $data, $binary);
    }

    /**
     * @group crypto
     * @group cryptoHashFile
     *
     * @dataProvider provideValidHashFileValues
     */
    public function testValidHashFileValues(string $expected, string|null $data, string $algo, bool $binary): void
    {
        $file = vfsStream::newFile('data.txt')->withContent($data)->at($this->fs);

        self::assertEquals($expected, Crypto::hashFile($file->url(), $algo, $binary));
    }

    /**
     * @group crypto
     * @group cryptoHex
     *
     * @dataProvider provideValidHexValues
     */
    public function testValidHexValues(int $length): void
    {
        $hex = Crypto::hex($length);

        self::assertEquals($length, strlen($hex));
        self::assertMatchesRegularExpression('/[a-fA-F\d]*/', $hex);
    }

    /**
     * @group crypto
     * @group cryptoHex
     *
     * @dataProvider provideInvalidHexValues
     */
    public function testInvalidHexValues(int $length): void
    {
        $this->expectException(ValueError::class);

        Crypto::hex($length);
    }

    /**
     * @param mixed[] $options
     *
     * @group crypto
     * @group cryptoCreatePasswordHash
     *
     * @dataProvider provideValidCreatePasswordHashValues
     */
    public function testValidCreatePasswordHashValues(
        string $prefix,
        string $password,
        string $algo,
        array  $options
    ): void
    {
        self::assertStringStartsWith($prefix, Crypto::createPasswordHash($password, $algo, $options));
    }

    /**
     * @param mixed[] $options
     *
     * @group crypto
     * @group cryptoCreatePasswordHash
     *
     * @dataProvider provideInvalidCreatePasswordHashValues
     */
    public function testInvalidCreatePasswordHashValues(
        string $prefix,
        string $password,
        string $algo,
        array  $options
    ): void
    {
        $this->expectException(ValueError::class);

        Crypto::createPasswordHash($password, $algo, $options);
    }

    /**
     * @group crypto
     * @group cryptoVerifyPassword
     *
     * @dataProvider provideValidVerifyPasswordValues
     */
    public function testValidVerifyPasswordValues(
        string $password,
        string $hash
    ): void
    {
        self::assertTrue(Crypto::verifyPassword($password, $hash));
    }

    /**
     * @group crypto
     * @group cryptoVerifyPassword
     *
     * @dataProvider provideInvalidVerifyPasswordValues
     */
    public function testInvalidVerifyPasswordValues(
        string $password,
        string $hash
    ): void
    {
        self::assertFalse(Crypto::verifyPassword($password, $hash));
    }

    /**
     * @group crypto
     * @group cryptoViaKey
     * @group cryptoEncryptViaPublicKey
     *
     * @dataProvider provideValidEncryptViaPublicKeyValues
     */
    public function testValidEncryptViaPublicKeyValues(
        string $content,
        string $privateKey,
        string $publicKey,
        int    $padding
    ): void
    {
        if ($publicKey === '@file') {
            $publicKey = vfsStream::newFile('public.pem')->withContent($this->publicKey)->at($this->fs)->url();
        }

        $encrypted = Crypto::encryptViaPublicKey($content, $publicKey, $padding);

        self::assertEquals($content, Crypto::decryptViaPrivateKey($encrypted, $privateKey, $padding));
    }

    /**
     * @group crypto
     * @group cryptoViaKey
     * @group cryptoEncryptViaPublicKey
     *
     * @dataProvider provideInvalidEncryptViaPublicKeyValues
     */
    public function testInvalidEncryptViaPublicKeyValues(
        string $content,
        string $publicKey,
        int    $padding
    ): void
    {
        $this->expectException(RuntimeException::class);

        Crypto::encryptViaPublicKey($content, $publicKey, $padding);
    }

    /**
     * @group crypto
     * @group cryptoViaKey
     * @group cryptoDecryptViaPrivateKey
     *
     * @dataProvider provideValidDecryptViaPrivateKeyValues
     */
    public function testValidDecryptViaPrivateKeyValues(
        string $expected,
        string $encrypted,
        string $privateKey,
        int    $padding
    ): void
    {
        if ($privateKey === '@file') {
            $privateKey = vfsStream::newFile('private.pem')->withContent($this->privateKey)->at($this->fs)->url();
        }

        self::assertEquals($expected, Crypto::decryptViaPrivateKey($encrypted, $privateKey, $padding));
    }

    /**
     * @group crypto
     * @group cryptoViaKey
     * @group cryptoDecryptViaPrivateKey
     *
     * @dataProvider provideInvalidDecryptViaPrivateKeyValues
     */
    public function testInvalidDecryptViaPrivateKeyValues(
        string $encrypted,
        string $privateKey,
        int    $padding
    ): void
    {
        $this->expectException(RuntimeException::class);

        Crypto::decryptViaPrivateKey($encrypted, $privateKey, $padding);
    }

    /**
     * @group crypto
     * @group cryptoViaKey
     * @group cryptoEncrypt
     *
     * @dataProvider provideValidEncryptValues
     */
    public function testValidEncryptValues(
        string $content,
        string $passphrase,
        string $secretKey,
        string $cipher,
        string $hashAlgo
    ): void
    {
        $encrypted = Crypto::encrypt($content, $passphrase, $secretKey, $cipher, $hashAlgo);

        self::assertEquals($content, Crypto::decrypt($encrypted, $passphrase, $secretKey, $cipher, $hashAlgo));
    }

    /**
     * @group crypto
     * @group cryptoViaKey
     * @group cryptoEncrypt
     *
     * @dataProvider provideInvalidEncryptValues
     */
    public function testInvalidEncryptValues(
        string $content,
        string $passphrase,
        string $secretKey,
        string $cipher,
        string $hashAlgo
    ): void
    {
        $this->expectException(RuntimeException::class);

        Crypto::encrypt($content, $passphrase, $secretKey, $cipher, $hashAlgo);
    }

    /**
     * @group crypto
     * @group cryptoViaKey
     * @group cryptoDecrypt
     *
     * @dataProvider provideValidDecryptValues
     */
    public function testValidDecryptValues(
        string $expected,
        string $content,
        string $passphrase,
        string $secretKey,
        string $cipher,
        string $hashAlgo
    ): void
    {
        self::assertEquals($expected, Crypto::decrypt($content, $passphrase, $secretKey, $cipher, $hashAlgo));
    }

    /**
     * @group crypto
     * @group cryptoViaKey
     * @group cryptoDecrypt
     *
     * @dataProvider provideInvalidDecryptValues
     */
    public function testInvalidDecryptValues(
        string $content,
        string $passphrase,
        string $secretKey,
        string $cipher,
        string $hashAlgo
    ): void
    {
        $this->expectException(RuntimeException::class);

        Crypto::decrypt($content, $passphrase, $secretKey, $cipher, $hashAlgo);
    }

    # endregion

    # region Values

    /**
     * @return mixed[]
     */
    public function provideValidIdentityValues(): array
    {
        return [
            'P: ID | L: 16'                   => ['ID', 16],
            'P: example | L: 1'               => ['example', 1],
            'P: SecID | L: 128'               => ['SecID', 128],
            'P: 2000 | L: 8'                  => ['2000', 8],
            'P: X50x | L: 4'                  => ['X50x', 4],
            'P: "" | L: 2'                    => ['', 2],
            'P: " " | L: 2'                   => [' ', 7],
            'P: AbCdEfGhIjKlMnz123450 | L: 3' => ['AbCdEfGhIjKlMnz123450', 3],
            'P: #XX- | L: 6'                  => ['#XX-', 6],
            'P: \}(/&$%"!<{?+\'~> | L: 5'     => ['\}(/&$%"!<{?+\'~>', 5],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidIdentityValues(): array
    {
        return [
            'P: ID | L: 0'  => ['ID', 0],
            'P: ID | L: -2' => ['ID', -2],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidHashValues(): array
    {
        return [
            'crc32'     => ['eabfc578', Crypto::HASH_CRC32, 'CryptoHashTest#1337', false],
            'md5'       => ['f94f9157c4273f0c4630b9f0b94ad14a', Crypto::HASH_MD5, 'CryptoHashTest#1337', false],
            'sha1'      => ['0c179b9906c01ff9398e1b9be104ac4c0b626e31', Crypto::HASH_SHA1, 'CryptoHashTest#1337', false],
            'whirlpool' => [
                '22bab582d406cfe0b3ab2e567613c3bc2b769af010d2e136ee7bde8775ef1f4b54d8aa58e8347681bc5cce4bf5a6092f5a6c264c29f3e46e93806e0f69b82027',
                Crypto::HASH_WHIRLPOOL,
                'CryptoHashTest#1337',
                false
            ],
            'xxh3'      => ['8b36c339951b4ca7', Crypto::HASH_XXH3, 'CryptoHashTest#1337', false],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidHashValues(): array
    {
        return [
            'invalid algorithmus' => ['invalidAlgorithmus', 'CryptoHashTest#1337', false],
            'missing algorithmus' => ['invalidAlgorithmus', '', false],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidHashFileValues(): array
    {
        $data = <<<data
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
At vero eos et accusam et justo duo dolores et ea rebum.
Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
At vero eos et accusam et justo duo dolores et ea rebum.
Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.

data;

        return [
            'crc32'      => ['751a2f7e', $data, Crypto::HASH_CRC32, false],
            'md5'        => ['85778b52e9272520341d48541dc00fbe', $data, Crypto::HASH_MD5, false],
            'sha1'       => ['8b6551cb01d2c41d48d4701224a8ef3ead7bf115', $data, Crypto::HASH_SHA1, false],
            'whirlpool'  => [
                '19bae053fdaca829ced08ddf063ff16973329fda062a0803bfaa881b9468f4ba8271046fc2eec6452895682aa62f95fa83e14f00d500cfad069334890c3251aa',
                $data,
                Crypto::HASH_WHIRLPOOL,
                false
            ],
            'xxh3'       => ['cbc181ecba15c91e', $data, Crypto::HASH_XXH3, false],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidHexValues(): array
    {
        return [
            '8'   => [8],
            '16'  => [16],
            '32'  => [32],
            '64'  => [64],
            '128' => [128],
            '256' => [256],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidHexValues(): array
    {
        return [
            '0'  => [0],
            '-5' => [-5],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidCreatePasswordHashValues(): array
    {
        return [
            'BCRYPT' => ['$2y$10$', 'SecretPassword123', PASSWORD_BCRYPT, []],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidCreatePasswordHashValues(): array
    {
        return [
            'invalid algorithmus' => ['$2y$10$', 'SecretPassword123', 'invalidAlgorithmus', []],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidVerifyPasswordValues(): array
    {
        return [
            'SecretPassword123' => ['SecretPassword123', '$2y$10$qdn3tXqJWfCw7cwvZ3EcUO/UQW86VF/CtUX/cnIxjyQ75R4QFbFGe'],
            '©[YÚÙðÜxÇò%ðDSÿ©'  => ['©[YÚÙðÜxÇò%ðDSÿ©', '$2y$10$DELfH8MWGSW3d6CHPVDGjOxFddt.hxjFhVSaAvQYVDhnB2UYw4mwS'],
            ':D2BA1vAi0BrsQc\\' => [':D2BA1vAi0BrsQc\\', '$2y$10$z7U55V4Yu4M8BjvvaFzr0OHA3Ya061TfltPboHtp3MeyatzyUHXmK'],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidVerifyPasswordValues(): array
    {
        return [
            'wrong hash'      => ['SecretPassword123', '$2y$10$z7U55V4Yu4M8BjvvaFzr0OHA3Ya061TfltPboHtp3MeyatzyUHXmK'],
            'missing char'    => ['SecretPassword123', '$2y$10$qdn3tXqJWfCw7cwvZ3EcUO/UQW86VF/CtUX/cnIxjyQ75R4QFbFG'],
            'incomplete hash' => ['SecretPassword123', '$2y$10$'],
            'empty string'    => ['SecretPassword123', ''],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidEncryptViaPublicKeyValues(): array
    {
        return [
            'string' => ['VerySecretContent', $this->privateKey, $this->publicKey, 1],
            'file'   => ['VerySecretContent', $this->privateKey, '@file', 1],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidEncryptViaPublicKeyValues(): array
    {
        return [
            'missing'   => ['VerySecretContent', vfsStream::url('fs/public.pem'), 1],
            'empty'     => ['VerySecretContent', '', 1],
            'malformed' => ['VerySecretContent', '-----BEGIN PUBLIC KEY----- -----END PUBLIC KEY-----', 1],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidDecryptViaPrivateKeyValues(): array
    {
        $encrypted = base64_decode($this->encrypted);

        return [
            'string' => ['VerySecretContent', $encrypted, $this->privateKey, 1],
            'file'   => ['VerySecretContent', $encrypted, '@file', 1],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidDecryptViaPrivateKeyValues(): array
    {
        $encrypted = base64_decode($this->encrypted);

        return [
            'missing'   => [$encrypted, vfsStream::url('fs/privte.pem'), 1],
            'empty'     => [$encrypted, '', 1],
            'malformed' => [$encrypted, '-----BEGIN PUBLIC KEY----- -----END PUBLIC KEY-----', 1],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidEncryptValues(): array
    {
        return [
            'VerySecretContent' => [
                'VerySecretContent',
                'MyV3ryS3(r3tP4$$word',
                '+V3eryS3(retK3y#',
                Crypto::CIPHER_AES_256_GCM,
                Crypto::HASH_SHA256
            ],
            'empty passphrase' => [
                'VerySecretContent',
                '',
                '+V3eryS3(retK3y#',
                Crypto::CIPHER_AES_256_GCM,
                Crypto::HASH_SHA256
            ],
            'empty secret' => [
                'VerySecretContent',
                'MyV3ryS3(r3tP4$$word',
                '',
                Crypto::CIPHER_AES_256_GCM,
                Crypto::HASH_SHA256
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidEncryptValues(): array
    {
        return [
            'missing cipher' => ['VerySecretContent', '', '', '', Crypto::HASH_SHA256],
            'wrong cipher'   => ['VerySecretContent', '', '', 'noCipher', Crypto::HASH_SHA256],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidDecryptValues(): array
    {
        return [
            'VerySecretContent' => [
                'VerySecretContent',
                'AwE8sVmgbTbGQ6gPlEmgLzZkLGEvZmWzMwtlZzSvAwt3AwRmBGp1BJWyAmplLwAvAQuyZJL3AwD2BGLjAJHmLJH5ZTAuLmWuAwp2MJD4AwxQrXjaozE1nzydo3VeE1OYLIyCpT1un0SRBRx9',
                'MyV3ryS3(r3tP4$$word',
                '+V3eryS3(retK3y#',
                Crypto::CIPHER_AES_256_GCM,
                Crypto::HASH_SHA256
            ],
            'empty passphrase' => [
                'VerySecretContent',
                'AwE8sAGUpOE6GBUA0ATUOQt1A2D3AzIuLGp4ZQN3Lmt0AwyzAGVmMwR4LwL2MwxmAGx2ZGH4LGMuATEvZQt0AmSyBQEwZwp5BGH4BGp1ZzLvEPSHq3MUIKZ4E3uOERSaLxuKFSS0pRunJwD9',
                '',
                '+V3eryS3(retK3y#',
                Crypto::CIPHER_AES_256_GCM,
                Crypto::HASH_SHA256
            ],
            'empty secret' => [
                'VerySecretContent',
                'AwE8sXlNcMj11ArcQLnSdQp5AQDjAzDmMGD2BQt3MGMuZzEuZQAwMQRjBQuzAmH3A2SzLmV4ZJV1MQLmAwSxA2Z5ZGDlL2R4AQR3LzH2MQqSSeWZFSMvBQMOEGyLL1SdHmARAJ5uA0H4HyH9',
                'MyV3ryS3(r3tP4$$word',
                '',
                Crypto::CIPHER_AES_256_GCM,
                Crypto::HASH_SHA256
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidDecryptValues(): array
    {
        return [
            'missing content' => [
                'VerySecretContent',
                '',
                'MyV3ryS3(r3tP4$$word',
                '+V3eryS3(retK3y#',
                Crypto::CIPHER_AES_256_GCM,
                Crypto::HASH_SHA256
            ],
        ];
    }

    # endregion
}
