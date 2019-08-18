<?php declare(strict_types=1);

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Http;

/**
 * Class HTTP
 *
 * @package Vection\Component\Http
 */
class HTTP
{
    const PHRASE_100 = 'Continue';
    const PHRASE_101 = 'Switching Protocols';
    const PHRASE_102 = 'Processing';
    const PHRASE_200 = 'OK';
    const PHRASE_201 = 'Created';
    const PHRASE_202 = 'Accepted';
    const PHRASE_203 = 'Non-Authoritative Information';
    const PHRASE_204 = 'No Content';
    const PHRASE_205 = 'Reset Content';
    const PHRASE_206 = 'Partial Content';
    const PHRASE_207 = 'Multi-status';
    const PHRASE_208 = 'Already Reported';
    const PHRASE_300 = 'Multiple Choices';
    const PHRASE_301 = 'Moved Permanently';
    const PHRASE_302 = 'Found';
    const PHRASE_303 = 'See Other';
    const PHRASE_304 = 'Not Modified';
    const PHRASE_305 = 'Use Proxy';
    const PHRASE_306 = 'Switch Proxy';
    const PHRASE_307 = 'Temporary Redirect';
    const PHRASE_400 = 'Bad Request';
    const PHRASE_401 = 'Unauthorized';
    const PHRASE_402 = 'Payment Required';
    const PHRASE_403 = 'Forbidden';
    const PHRASE_404 = 'Not Found';
    const PHRASE_405 = 'Method Not Allowed';
    const PHRASE_406 = 'Not Acceptable';
    const PHRASE_407 = 'Proxy Authentication Required';
    const PHRASE_408 = 'Request Time-out';
    const PHRASE_409 = 'Conflict';
    const PHRASE_410 = 'Gone';
    const PHRASE_411 = 'Length Required';
    const PHRASE_412 = 'Precondition Failed';
    const PHRASE_413 = 'Request Entity Too Large';
    const PHRASE_414 = 'Request-URI Too Large';
    const PHRASE_415 = 'Unsupported Media Type';
    const PHRASE_416 = 'Requested range not satisfiable';
    const PHRASE_417 = 'Expectation Failed';
    const PHRASE_418 = 'I\'m a teapot';
    const PHRASE_422 = 'Unprocessable Entity';
    const PHRASE_423 = 'Locked';
    const PHRASE_424 = 'Failed Dependency';
    const PHRASE_425 = 'Unordered Collection';
    const PHRASE_426 = 'Upgrade Required';
    const PHRASE_428 = 'Precondition Required';
    const PHRASE_429 = 'Too Many Requests';
    const PHRASE_431 = 'Request Header Fields Too Large';
    const PHRASE_451 = 'Unavailable For Legal Reasons';
    const PHRASE_500 = 'Internal Server Error';
    const PHRASE_501 = 'Not Implemented';
    const PHRASE_502 = 'Bad Gateway';
    const PHRASE_503 = 'Service Unavailable';
    const PHRASE_504 = 'Gateway Time-out';
    const PHRASE_505 = 'HTTP Version not supported';
    const PHRASE_506 = 'Variant Also Negotiates';
    const PHRASE_507 = 'Insufficient Storage';
    const PHRASE_508 = 'Loop Detected';
    const PHRASE_511 = 'Network Authentication Required';

    const PHRASES = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required'
    ];

    /**
     * @param int $code
     *
     * @return string
     */
    public static function getPhrase(int $code): string
    {
        if( ! isset(self::PHRASES[$code]) ){
            throw new \RuntimeException("Invalid HTTP status code ($code). ");
        }

        return self::PHRASES[$code];
    }
}