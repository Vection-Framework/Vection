<?php

namespace Vection\Component\Common\Tests;

use JsonException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use StdClass;
use Vection\Component\Common\Json;
use Vection\Component\Common\VArray;

/**
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class JsonTest extends TestCase
{
    # region Setup

    private vfsStreamDirectory $fs;

    public function setUp(): void
    {
        $this->fs = vfsStream::setup('fs');
    }

    # endregion

    # region Tests

    /**
     * @group json
     * @group jsonParse
     *
     * @dataProvider provideValidParseValues
     *
     * @throws JsonException
     */
    public function testValidParseValues(array $data): void
    {
        $json = json_encode($data, JSON_THROW_ON_ERROR);

        self::assertEquals(
            new VArray(json_decode($json, true, 512, JSON_THROW_ON_ERROR)), Json::parse($json)
        );
    }

    /**
     * @group json
     * @group jsonRead
     *
     * @dataProvider provideValidReadValues
     *
     * @throws JsonException
     */
    public function testValidReadValues(array $data): void
    {
        $json = json_encode($data, JSON_THROW_ON_ERROR);

        $file = vfsStream::newFile('data.json')
            ->withContent($json)->at($this->fs);

        self::assertEquals(new VArray(json_decode($json, true, 512, JSON_THROW_ON_ERROR)), Json::read($file->url()));
    }

    /**
     * @throws JsonException
     *
     * @group json
     * @group jsonEncode
     *
     * @dataProvider provideValidEncodeValues
     */
    public function testValidEncodeValues(VArray $array, bool $pretty = true, int $flags = 0, int $depth = 512): void
    {
        if ($pretty) {
            $flags += JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS;
        }

        self::assertEquals(
            json_encode($array, $flags | JSON_THROW_ON_ERROR, $depth),
            Json::encode($array, $pretty, $flags, $depth)
        );
    }

    /**
     * @group json
     * @group jsonToFile
     *
     * @dataProvider provideValidToFileValues
     */
    public function testValidToFileValues(
        string $filePath,
        VArray $array,
        bool   $pretty = true,
        int    $flags  = 0,
        int    $depth  = 512
    ): void
    {
        Json::toFile(vfsStream::url($filePath), $array, $pretty, $flags, $depth);

        self::assertTrue($this->fs->hasChild($filePath));
        self::assertEquals(
            Json::encode($array, $pretty, $flags, $depth),
            $this->fs->getChild($filePath)->getContent()
        );
    }

    # endregion

    # region Values

    /**
     * @return mixed[]
     */
    public function provideValidParseValues(): array
    {
        return [
            'array' => [
                [
                    'bool'   => true,
                    'string' => 'Text',
                    'int'    => 123,
                    'float'  => 123.123,
                    'array'  => ['Text', 123, 123.123],
                    'object' => new StdClass(),
                ],
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidReadValues(): array
    {
        return [
            'array' => [
                [
                    'bool'   => true,
                    'string' => 'Text',
                    'int'    => 123,
                    'float'  => 123.123,
                    'array'  => ['Text', 123, 123.123],
                    'object' => new StdClass(),
                ],
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidEncodeValues(): array
    {
        return [
            'pretty' => [
                new VArray([
                    'bool'   => true,
                    'string' => 'Text',
                    'int'    => 123,
                    'float'  => 123.123,
                    'array'  => ['Text', 123, 123.123],
                    'object' => new StdClass(),
                ]),
                true,
                0,
                512
            ],
            'not pretty' => [
                new VArray([
                    'bool'   => true,
                    'string' => 'Text',
                    'int'    => 123,
                    'float'  => 123.123,
                    'array'  => ['Text', 123, 123.123],
                    'object' => new StdClass(),
                ]),
                false,
                0,
                512
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidToFileValues(): array
    {
        return [
            'pretty' => [
                'fs/data.json',
                new VArray([
                    'bool'   => true,
                    'string' => 'Text',
                    'int'    => 123,
                    'float'  => 123.123,
                    'array'  => ['Text', 123, 123.123],
                    'object' => new StdClass(),
                ]),
                true,
                0,
                512
            ],
            'not pretty' => [
                'fs/data.json',
                new VArray([
                    'bool'   => true,
                    'string' => 'Text',
                    'int'    => 123,
                    'float'  => 123.123,
                    'array'  => ['Text', 123, 123.123],
                    'object' => new StdClass(),
                ]),
                false,
                0,
                512
            ],
        ];
    }

    # endregion
}
