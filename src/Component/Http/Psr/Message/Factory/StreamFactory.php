<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Http\Psr\Message\Factory;

use InvalidArgumentException;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Vection\Component\Http\Psr\Message\Stream;

/**
 * Class StreamFactory
 *
 * @package Vection\Component\Http\Psr\Factory
 */
class StreamFactory implements StreamFactoryInterface
{

    /**
     * Create a new stream from a string.
     *
     * The stream SHOULD be created with a temporary resource.
     *
     * @param string $content String content with which to populate the stream.
     *
     * @return StreamInterface
     */
    public function createStream(string $content = ''): StreamInterface
    {
        $resource = fopen('php://temp', 'rw+b');

        if( $content ){
            fwrite($resource, $content);
            fseek($resource, 0);
        }

        return $this->createStreamFromResource($resource);
    }

    /**
     * Create a stream from an existing file.
     *
     * The file MUST be opened using the given mode, which may be any mode
     * supported by the `fopen` function.
     *
     * The `$filename` MAY be any string supported by `fopen()`.
     *
     * @param string $filename Filename or stream URI to use as basis of stream.
     * @param string $mode     Mode with which to open the underlying filename/stream.
     *
     * @return StreamInterface
     * @throws RuntimeException If the file cannot be opened.
     * @throws InvalidArgumentException If the mode is invalid.
     */
    public function createStreamFromFile(string $filename, string $mode = 'rb'): StreamInterface
    {
        if( stripos($filename, 'php://') !== 0 && ! file_exists($filename) ){
            throw new RuntimeException("Unable to create stream from file: File not found ($filename).");
        }

        if( ! isset(Stream::RESOURCE_MODES['read'][$mode]) && ! isset(Stream::RESOURCE_MODES['write'][$mode]) ){
            throw new InvalidArgumentException("Unable to open stream: Invalid mode '$mode'.");
        }

        $resource = fopen($filename, $mode);

        if( $resource === false ){
            throw new RuntimeException("Unable to open stream with file ($filename).");
        }

        return $this->createStreamFromResource($resource);
    }

    /**
     * Create a new stream from an existing resource.
     *
     * The stream MUST be readable and may be writable.
     *
     * @param resource $resource PHP resource to use as basis of stream.
     *
     * @return StreamInterface
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        return new Stream($resource);
    }
}