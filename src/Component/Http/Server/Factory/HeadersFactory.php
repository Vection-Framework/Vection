<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Http\Server\Factory;

use Vection\Component\Http\Headers;
use Vection\Component\Http\Server\Environment;

/**
 * Class HeadersFactory
 *
 * @package Vection\Component\Http\Server\Factory
 */
class HeadersFactory
{

    /** @var Environment */
    protected $environment;

    /**
     * HeadersFactory constructor.
     *
     * @param Environment $environment
     *
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Creates an object from type Header that contains all headers
     * send by the client to server.
     *
     * @return Headers
     */
    public function createHeaders(): Headers
    {
        # Create header parameters
        $headers = new Headers();

        # Content info does not appear in $_SERVER as $_SERVER['HTTP_CONTENT_TYPE'].
        # PHP removes these (per CGI/1.1 specification[1]) from the HTTP_ match group
        $exceptionalHeaders = [
            'CONTENT_TYPE' => 'content-type',
            'CONTENT_LENGTH' => 'content-length',
            'CONTENT_MD5' => 'content-md5'
        ];

        foreach ( $this->environment->toArray() as $name => $value ) {
            if ( stripos($name, 'HTTP_') === 0 ) {
                $name = strtolower(substr(str_replace('_', '-', $name), 5));
                $headers->set($name, $value);
            } else if (isset($exceptionalHeaders[$name])) {
                $headers->set($exceptionalHeaders[$name], $value);
            }
        }

        if ( $this->environment->has('REDIRECT_HTTP_AUTHORIZATION') && ! $headers->has('Authorization') ) {
            $headers->set('Authorization', $this->environment->get('REDIRECT_HTTP_AUTHORIZATION'));
        }

        $authUser = trim($this->environment->getPHPAuthUser());
        $authPw   = trim($this->environment->getPHPAuthPW());

        if ( $authUser ) {
            $headers->set('Authorization', 'Basic '.base64_encode($authUser.':'.$authPw));
        }

        return $headers;
    }
}
