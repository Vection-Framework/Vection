<?php

namespace Vection\Component\Common\Tests;

use InvalidArgumentException;
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
    public function testValidHashFileValues(string $expected, string $algo, string|null $data, bool $binary): void
    {
        self::assertEquals($expected, Crypto::hashFile($algo, $data, $binary));
    }

    /**
     * @group crypto
     * @group cryptoHashFile
     *
     * @dataProvider provideInvalidHashFileValues
     */
    public function testInvalidHashFileValues(string $path, string $algo, bool $binary): void
    {
        $this->expectException(ValueError::class);

        Crypto::hashFile($path, $algo, $binary);
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
        $file = __DIR__.'/Fixtures/lorem-ipsum.txt';

        return [
            'crc32'      => ['751a2f7e', $file, Crypto::HASH_CRC32, false],
            'md5'        => ['85778b52e9272520341d48541dc00fbe', $file, Crypto::HASH_MD5, false],
            'sha1'       => ['8b6551cb01d2c41d48d4701224a8ef3ead7bf115', $file, Crypto::HASH_SHA1, false],
            'whirlpool'  => [
                '19bae053fdaca829ced08ddf063ff16973329fda062a0803bfaa881b9468f4ba8271046fc2eec6452895682aa62f95fa83e14f00d500cfad069334890c3251aa',
                $file,
                Crypto::HASH_WHIRLPOOL,
                false
            ],
            'xxh3'       => ['cbc181ecba15c91e', $file, Crypto::HASH_XXH3, false],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidHashFileValues(): array
    {
        $file = __DIR__.'/Fixtures/lorem-ipsum.txt';

        return [
            'invalid algorithmus' => [$file, 'invalidAlgorithmus', false],
            'missing algorithmus' => [$file, '', false],
            'missing path'        => ['', 'md5', false],
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
        $privateKey = __DIR__.'/Fixtures/private.pem';
        $publicKey  = __DIR__.'/Fixtures/public.pem';

        return [
            'VerySecretContent' => ['VerySecretContent', $privateKey, $publicKey, 1],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidEncryptViaPublicKeyValues(): array
    {
        return [
            'missing public key'   => ['VerySecretContent', '', 1],
            'malformed public key' => ['VerySecretContent', __DIR__.'/Fixtures/lorem-ipsum.txt', 1],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidDecryptViaPrivateKeyValues(): array
    {
        return [
            'encrypted binary file' => [
                'VerySecretContent',
                file_get_contents(__DIR__.'/Fixtures/encrypted.binary'),
                __DIR__.'/Fixtures/private.pem',
                1
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidDecryptViaPrivateKeyValues(): array
    {
        return [
            'no encrypted file' => [
                file_get_contents(__DIR__.'/Fixtures/lorem-ipsum.txt'),
                __DIR__.'/Fixtures/private.pem',
                1
            ],
            'missing private key' => [
                file_get_contents(__DIR__.'/Fixtures/encrypted.binary'),
                '',
                1
            ],
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
