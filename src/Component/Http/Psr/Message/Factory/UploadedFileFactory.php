<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Http\Psr\Message\Factory;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;
use Vection\Component\Http\Psr\Message\UploadedFile;
use const UPLOAD_ERR_OK;

/**
 * Class UploadedFileFactory
 *
 * @package Vection\Component\Http\Psr\Message\Factory
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class UploadedFileFactory implements UploadedFileFactoryInterface
{

    /**
     * Create a new uploaded file.
     *
     * If a size is not provided it will be determined by checking the size of
     * the file.
     *
     * @see http://php.net/manual/features.file-upload.post-method.php
     * @see http://php.net/manual/features.file-upload.errors.php
     *
     * @param StreamInterface $stream          Underlying stream representing the
     *                                         uploaded file content.
     * @param int|null        $size            in bytes
     * @param int             $error           PHP file upload error
     * @param string|null     $clientFilename  Filename as provided by the client, if any.
     * @param string|null     $clientMediaType Media type as provided by the client, if any.
     *
     * @return UploadedFileInterface
     *
     * @throws InvalidArgumentException If the file resource is not readable.
     */
    public function createUploadedFile(
        StreamInterface $stream,
        int|null $size = null,
        int $error = UPLOAD_ERR_OK,
        string|null $clientFilename = null,
        string|null $clientMediaType = null
    ): UploadedFileInterface
    {
        if ( ! $stream->isReadable() ) {
            throw new InvalidArgumentException('Unable to create uploaded file: Stream is not readable.');
        }

        return new UploadedFile($stream, $size ?: $stream->getSize(), $error, $clientFilename, $clientMediaType);
    }
}
