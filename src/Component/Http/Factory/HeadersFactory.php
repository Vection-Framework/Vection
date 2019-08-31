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

namespace Vection\Component\Http\Factory;


use Vection\Component\Http\Headers;

/**
 * Class HeadersFactory
 *
 * @package Vection\Component\Http\Factory
 */
class HeadersFactory
{
    /**
     *
     * @return Headers
     */
    public static function createFromServer(): Headers
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

        foreach( $_SERVER as $name => $value ){
            if( stripos($name, 'HTTP_') === 0 ){
                $name = substr(strtolower(str_replace('_', '-', $name)), 5);
                $headers->set($name, $value);
            }elseif(isset($exceptionalHeaders[$name])){
                $headers->set($exceptionalHeaders[$name], $value);
            }
        }

        if( ! $headers->has('Authorization') && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) ){
            $headers->set('Authorization', $_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
        }

        $authUser = trim($_SERVER['PHP_AUTH_USER'] ?? '');
        $authPw = trim($_SERVER['PHP_AUTH_PW'] ?? '');

        if( $authUser ){
            $headers->set('Authorization', 'Basic '.base64_encode($authUser.':'.$authPw));
        }

        return $headers;
    }
}