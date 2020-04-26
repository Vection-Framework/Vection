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

namespace Vection\Component\Http;

use RuntimeException;

/**
 * Class Status
 *
 * @package Vection\Component\Http
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Status
{
    public const PHRASE_100 = 'Continue';
    public const PHRASE_101 = 'Switching Protocols';
    public const PHRASE_102 = 'Processing';
    public const PHRASE_200 = 'OK';
    public const PHRASE_201 = 'Created';
    public const PHRASE_202 = 'Accepted';
    public const PHRASE_203 = 'Non-Authoritative Information';
    public const PHRASE_204 = 'No Content';
    public const PHRASE_205 = 'Reset Content';
    public const PHRASE_206 = 'Partial Content';
    public const PHRASE_207 = 'Multi-status';
    public const PHRASE_208 = 'Already Reported';
    public const PHRASE_300 = 'Multiple Choices';
    public const PHRASE_301 = 'Moved Permanently';
    public const PHRASE_302 = 'Found';
    public const PHRASE_303 = 'See Other';
    public const PHRASE_304 = 'Not Modified';
    public const PHRASE_305 = 'Use Proxy';
    public const PHRASE_306 = 'Switch Proxy';
    public const PHRASE_307 = 'Temporary Redirect';
    public const PHRASE_400 = 'Bad Request';
    public const PHRASE_401 = 'Unauthorized';
    public const PHRASE_402 = 'Payment Required';
    public const PHRASE_403 = 'Forbidden';
    public const PHRASE_404 = 'Not Found';
    public const PHRASE_405 = 'Method Not Allowed';
    public const PHRASE_406 = 'Not Acceptable';
    public const PHRASE_407 = 'Proxy Authentication Required';
    public const PHRASE_408 = 'Request Time-out';
    public const PHRASE_409 = 'Conflict';
    public const PHRASE_410 = 'Gone';
    public const PHRASE_411 = 'Length Required';
    public const PHRASE_412 = 'Precondition Failed';
    public const PHRASE_413 = 'Request Entity Too Large';
    public const PHRASE_414 = 'Request-URI Too Large';
    public const PHRASE_415 = 'Unsupported Media Type';
    public const PHRASE_416 = 'Requested range not satisfiable';
    public const PHRASE_417 = 'Expectation Failed';
    public const PHRASE_418 = 'I\'m a teapot';
    public const PHRASE_422 = 'Unprocessable Entity';
    public const PHRASE_423 = 'Locked';
    public const PHRASE_424 = 'Failed Dependency';
    public const PHRASE_425 = 'Unordered Collection';
    public const PHRASE_426 = 'Upgrade Required';
    public const PHRASE_428 = 'Precondition Required';
    public const PHRASE_429 = 'Too Many Requests';
    public const PHRASE_431 = 'Request Header Fields Too Large';
    public const PHRASE_451 = 'Unavailable For Legal Reasons';
    public const PHRASE_500 = 'Internal Server Error';
    public const PHRASE_501 = 'Not Implemented';
    public const PHRASE_502 = 'Bad Gateway';
    public const PHRASE_503 = 'Service Unavailable';
    public const PHRASE_504 = 'Gateway Time-out';
    public const PHRASE_505 = 'HTTP Version not supported';
    public const PHRASE_506 = 'Variant Also Negotiates';
    public const PHRASE_507 = 'Insufficient Storage';
    public const PHRASE_508 = 'Loop Detected';
    public const PHRASE_511 = 'Network Authentication Required';

    public const CODE_CONTINUE            = 100;
    public const CODE_SWITCHING_PROTOCOLS = 101;
    public const CODE_PROCESSING          = 102;
    public const CODE_EARLY_HINTS         = 103;
    public const CODE_OK       = 200;
    public const CODE_CREATED  = 201;
    public const CODE_ACCEPTED = 202;
    public const CODE_NON_AUTHORITATIVE_INFORMATION = 203;
    public const CODE_NO_CONTENT        = 204;
    public const CODE_RESET_CONTENT     = 205;
    public const CODE_PARTIAL_CONTENT   = 206;
    public const CODE_MULTI_STATUS      = 207;
    public const CODE_ALREADY_REPORTED  = 208;
    public const CODE_IM_USED           = 226;
    public const CODE_MULTIPLE_CHOICES  = 300;
    public const CODE_MOVED_PERMANENTLY = 301;
    public const CODE_FOUND        = 302;
    public const CODE_SEE_OTHER    = 303;
    public const CODE_NOT_MODIFIED = 304;
    public const CODE_USE_PROXY    = 305;
    public const CODE_RESERVED     = 306;
    public const CODE_TEMPORARY_REDIRECT   = 307;
    public const CODE_PERMANENTLY_REDIRECT = 308;
    public const CODE_BAD_REQUEST          = 400;
    public const CODE_UNAUTHORIZED         = 401;
    public const CODE_PAYMENT_REQUIRED     = 402;
    public const CODE_FORBIDDEN            = 403;
    public const CODE_NOT_FOUND            = 404;
    public const CODE_METHOD_NOT_ALLOWED   = 405;
    public const CODE_NOT_ACCEPTABLE       = 406;
    public const CODE_PROXY_AUTHENTICATION_REQUIRED = 407;
    public const CODE_REQUEST_TIMEOUT = 408;
    public const CODE_CONFLICT        = 409;
    public const CODE_GONE            = 410;
    public const CODE_LENGTH_REQUIRED = 411;
    public const CODE_PRECONDITION_FAILED      = 412;
    public const CODE_REQUEST_ENTITY_TOO_LARGE = 413;
    public const CODE_REQUEST_URI_TOO_LONG     = 414;
    public const CODE_UNSUPPORTED_MEDIA_TYPE   = 415;
    public const CODE_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    public const CODE_EXPECTATION_FAILED   = 417;
    public const CODE_I_AM_A_TEAPOT        = 418;
    public const CODE_MISDIRECTED_REQUEST  = 421;
    public const CODE_UNPROCESSABLE_ENTITY = 422;
    public const CODE_LOCKED            = 423;
    public const CODE_FAILED_DEPENDENCY = 424;

    public const PHRASES = [
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
        511 => 'Network Authentication Required',
    ];

    /**
     * @param int $code
     *
     * @return string
     */
    public static function getPhrase(int $code): string
    {
        if (!isset(self::PHRASES[$code])) {
            throw new RuntimeException("Invalid HTTP status code ($code). ");
        }

        return self::PHRASES[$code];
    }
}
