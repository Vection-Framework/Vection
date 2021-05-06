<?php

/*
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Common\Collection;

use JsonException;
use RuntimeException;

/**
 * Class Json
 *
 * @package Vection\Component\Common\Collection
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Json extends ArrayCollection
{
    /**
     * @param string $json
     * @param int    $depth
     * @param int    $options
     *
     * @return Json
     *
     * @throws JsonException
     */
    public static function parse(string $json, int $depth = 512, int $options = 0): self
    {
        return new self(json_decode($json, true, $depth, $options | JSON_THROW_ON_ERROR));
    }

    /**
     * @param string $filePath
     * @param int    $depth
     * @param int    $options
     *
     * @return Json
     *
     * @throws JsonException
     */
    public static function read(string $filePath, int $depth = 512, int $options = 0): self
    {
        if (!is_file($filePath)) {
            throw new RuntimeException("No such file: $filePath");
        }

        $content = file_get_contents($filePath);

        return self::parse($content, $depth, $options);
    }

    /**
     * @param int $flags
     * @param int $depth
     *
     * @return string
     *
     * @throws JsonException
     */
    public function toString(int $flags = 0, int $depth = 512): string
    {
        return json_encode($this, $flags|JSON_THROW_ON_ERROR, $depth);
    }

    /**
     * @return string
     *
     * @throws JsonException
     */
    public function __toString()
    {
        return $this->toString();
    }
}
