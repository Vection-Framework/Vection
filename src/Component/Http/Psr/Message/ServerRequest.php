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

namespace Vection\Component\Http\Psr\Message;

use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Common\Headers;
use Vection\Component\Http\Server\Environment;

/**
 * Class ServerRequest
 *
 * @package Vection\Component\Http\Psr\Message
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class ServerRequest extends Request implements ServerRequestInterface
{
    protected Environment $environment;
    protected array $cookieParams;
    protected array $queryParams;
    protected array $attributes;
    protected array|object|null $parsedBody;

    /** @var UploadedFileInterface[] */
    protected array $uploadedFiles;

    /**
     * ServerRequest constructor.
     *
     * @param string           $method
     * @param UriInterface     $uri
     * @param Headers          $headers
     * @param string           $version
     * @param Environment|null $environment
     */
    public function __construct(
        string $method, UriInterface $uri, Headers $headers, string $version = '1.1', Environment|null $environment = null
    )
    {
        parent::__construct($method, $uri, $headers, null, $version);
        $this->environment   = $environment ?: new Environment();
        $this->cookieParams  = $_COOKIE;
        $this->queryParams   = $_GET;
        $this->attributes    = [];
        $this->uploadedFiles = [];
    }

    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment,
     * typically derived from PHP's $_SERVER superglobal. The data IS NOT
     * REQUIRED to originate from $_SERVER.
     *
     * @return array
     */
    public function getServerParams()
    {
        return $this->environment->toArray();
    }

    /**
     * Retrieve cookies.
     *
     * Retrieves cookies sent by the client to the server.
     *
     * The data MUST be compatible with the structure of the $_COOKIE
     * superglobal.
     *
     * @return array
     */
    public function getCookieParams()
    {
        return $this->cookieParams;
    }

    /**
     * Return an instance with the specified cookies.
     *
     * The data IS NOT REQUIRED to come from the $_COOKIE superglobal, but MUST
     * be compatible with the structure of $_COOKIE. Typically, this data will
     * be injected at instantiation.
     *
     * This method MUST NOT update the related Cookie header of the request
     * instance, nor related values in the server params.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated cookie values.
     *
     * @param array $cookies Array of key/value pairs representing cookies.
     *
     * @return static
     */
    public function withCookieParams(array $cookies)
    {
        $request = clone $this;
        $request->cookieParams = $cookies;
        return $request;
    }

    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments, if any.
     *
     * Note: the query params might not be in sync with the URI or server
     * params. If you need to ensure you are only getting the original
     * values, you may need to parse the query string from `getUri()->getQuery()`
     * or from the `QUERY_STRING` server param.
     *
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Return an instance with the specified query string arguments.
     *
     * These values SHOULD remain immutable over the course of the incoming
     * request. They MAY be injected during instantiation, such as from PHP's
     * $_GET superglobal, or MAY be derived from some other value such as the
     * URI. In cases where the arguments are parsed from the URI, the data
     * MUST be compatible with what PHP's parse_str() would return for
     * purposes of how duplicate query parameters are handled, and how nested
     * sets are handled.
     *
     * Setting query string arguments MUST NOT change the URI stored by the
     * request, nor the values in the server params.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated query string arguments.
     *
     * @param array $query Array of query string arguments, typically from
     *                     $_GET.
     *
     * @return static
     */
    public function withQueryParams(array $query)
    {
        $request = clone $this;
        $request->queryParams = $query;
        return $request;
    }

    /**
     * Retrieve normalized file upload data.
     *
     * This method returns upload metadata in a normalized tree, with each leaf
     * an instance of Psr\Http\Message\UploadedFileInterface.
     *
     * These values MAY be prepared from $_FILES or the message body during
     * instantiation, or MAY be injected via withUploadedFiles().
     *
     * @return array An array tree of UploadedFileInterface instances; an empty
     *     array MUST be returned if no data is present.
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles ?: [];
    }

    /**
     * Create a new instance with the specified uploaded files.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated body parameters.
     *
     * @param array<UploadedFileInterface> $uploadedFiles An array tree of UploadedFileInterface instances.
     *
     * @return static
     * @throws InvalidArgumentException if an invalid structure is provided.
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        foreach ( $uploadedFiles as $uploadedFile ) {
            if ( ! $uploadedFile instanceof UploadedFileInterface) {
                throw new InvalidArgumentException(
                    'The first parameter of withUploadedFiles expect an array' .
                    ' contains only items of type UploadedFileInterface.'
                );
            }
        }

        $request = clone $this;
        $request->uploadedFiles = $uploadedFiles;
        return $request;
    }

    /**
     * Retrieve any parameters provided in the request body.
     *
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, this method MUST
     * return the contents of $_POST.
     *
     * Otherwise, this method may return any results of deserializing
     * the request body content; as parsing returns structured content, the
     * potential types MUST be arrays or objects only. A null value indicates
     * the absence of body content.
     *
     * @return null|array<mixed>|object The deserialized body parameters, if any.
     *     These will typically be an array or object.
     */
    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    /**
     * Return an instance with the specified body parameters.
     *
     * These MAY be injected during instantiation.
     *
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, use this method
     * ONLY to inject the contents of $_POST.
     *
     * The data IS NOT REQUIRED to come from $_POST, but MUST be the results of
     * deserializing the request body content. Deserialization/parsing returns
     * structured data, and, as such, this method ONLY accepts arrays or objects,
     * or a null value if nothing was available to parse.
     *
     * As an example, if content negotiation determines that the request data
     * is a JSON payload, this method could be used to create a request
     * instance with the deserialized parameters.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated body parameters.
     *
     * @param object|array<mixed>|null $data The deserialized body data. This will
     *                                typically be in an array or object.
     *
     * @return static
     * @throws InvalidArgumentException if an unsupported argument type is
     *     provided.
     */
    public function withParsedBody($data): ServerRequestInterface
    {
        if ( ! is_object($data) && ! is_array($data) && $data !== null ) {
            throw new InvalidArgumentException('Given data to withParsedBody MUST be array, object or null.');
        }

        $request = clone $this;
        $request->parsedBody = $data;
        return $request;
    }

    /**
     * Retrieve attributes derived from the request.
     *
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the request: e.g., the results of path
     * match operations; the results of decrypting cookies; the results of
     * deserializing non-form-encoded message bodies; etc. Attributes
     * will be application and request specific, and CAN be mutable.
     *
     * @return array<string, string> Attributes derived from the request.
     */
    public function getAttributes(): array
    {
        return $this->attributes ?: [];
    }

    /**
     * Retrieve a single derived request attribute.
     *
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     *
     * This method obviates the need for a hasAttribute() method, as it allows
     * specifying a default value to return if the attribute is not found.
     *
     * @param string $name    The attribute name.
     * @param mixed|null $default Default value to return if the attribute does not exist.
     *
     * @return mixed
     * @see getAttributes()
     */
    public function getAttribute($name, $default = null): mixed
    {
        if ( ! array_key_exists($name, $this->attributes) ) {
            return $default;
        }

        return $this->attributes[$name];
    }

    /**
     * Return an instance with the specified derived request attribute.
     *
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated attribute.
     *
     * @param string $name  The attribute name.
     * @param mixed  $value The value of the attribute.
     *
     * @return static
     * @see getAttributes()
     */
    public function withAttribute($name, $value): ServerRequestInterface
    {
        $request = clone $this;
        $request->attributes[$name] = $value;
        return $request;
    }

    /**
     * Return an instance that removes the specified derived request attribute.
     *
     * This method allows removing a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the attribute.
     *
     * @param string $name The attribute name.
     *
     * @return static
     * @see getAttributes()
     */
    public function withoutAttribute($name): ServerRequestInterface
    {
        $request = clone $this;

        if ( array_key_exists($name, $request->attributes) ) {
            unset($request->attributes[$name]);
        }

        return $request;
    }
}
