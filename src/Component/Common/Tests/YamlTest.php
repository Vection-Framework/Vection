<?php

namespace Vection\Component\Common\Tests;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use StdClass;
use Vection\Component\Common\VArray;
use Vection\Component\Common\Yaml;

/**
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class YamlTest extends TestCase
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
     */
    public function testValidParseValues(array $data): void
    {
        self::assertEquals(new VArray(yaml_parse(yaml_emit($data))), Yaml::parse(yaml_emit($data)));
    }

    /**
     * @group json
     * @group jsonRead
     *
     * @dataProvider provideValidReadValues
     */
    public function testValidReadValues(array $data): void
    {
        $file = vfsStream::newFile('data.yml')->withContent(yaml_emit($data))->at($this->fs);

        self::assertEquals(new VArray(yaml_parse(yaml_emit($data))), Yaml::read($file->url()));
    }

    /**
     * @group json
     * @group jsonEncode
     *
     * @dataProvider provideValidEncodeValues
     */
    public function testValidEncodeValues(VArray|array $data): void
    {
        self::assertEquals(yaml_emit(!$data instanceof VArray ?: $data->jsonSerialize()), Yaml::encode($data));
    }

    /**
     * @group json
     * @group jsonToFile
     *
     * @dataProvider provideValidToFileValues
     */
    public function testValidToFileValues(string $filePath, VArray $array): void
    {
        Yaml::toFile(vfsStream::url($filePath), $array);

        self::assertTrue($this->fs->hasChild($filePath));
        self::assertEquals(
            Yaml::encode($array),
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

    public function provideValidEncodeValues(): VArray|array
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
                ]
            ],
            'VArray' => [
                new VArray([
                    'bool'   => true,
                    'string' => 'Text',
                    'int'    => 123,
                    'float'  => 123.123,
                    'array'  => ['Text', 123, 123.123],
                    'object' => new StdClass(),
                ]),
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideValidToFileValues(): array
    {
        return [
            'file' => [
                'fs/data.yml',
                new VArray([
                    'bool'   => true,
                    'string' => 'Text',
                    'int'    => 123,
                    'float'  => 123.123,
                    'array'  => ['Text', 123, 123.123],
                    'object' => new StdClass(),
                ]),
            ],
        ];
    }

    # endregion
}
