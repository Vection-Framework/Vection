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

/*
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
 * Class Headers
 *
 * @package Vection\Component\Http
 */
class Headers
{
    /** @var array */
    protected $headers;

    /** @var array */
    protected $names;

    /**
     * Headers constructor.
     *
     * @param array $headers
     */
    public function __construct(array $headers = [])
    {
        $this->headers = $headers;

        foreach ( $headers as $name => $value ) {
            $this->names[strtolower($name)] = $name;
        }

        # TODO normilize the given array to psr standard
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->names[strtolower($name)]);
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return bool
     */
    public function hasValue(string $name, string $value): bool
    {
        $lowerName = strtolower($name);
        return isset($this->names[$lowerName]) && in_array($value, $this->headers[$this->names[$lowerName]], true);
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function get(string $name): array
    {
        $lowerName = strtolower($name);

        if ( ! isset($this->names[$lowerName]) ) {
            return [];
        }

        return $this->headers[$this->names[$lowerName]];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getLine(string $name): string
    {
        $lowerName = strtolower($name);

        if ( ! isset($this->names[$lowerName]) ) {
            return '';
        }

        return implode(', ', $this->get($name));
    }

    /**
     * @param string            $name
     * @param string|string[]   $value
     */
    public function add(string $name, $value): void
    {
        $lowerName = strtolower($name);

        if ( ! isset($this->names[$lowerName]) ) {
            $this->names[$lowerName] = $name;
        }

        $values = ($this->headers[$this->names[$lowerName]] ?? []);

        if ( is_string($value) && strpos($value, ',') !== false ) {
            # if the value is comma separated, then make array from it to aware consistency
            $value = array_map('trim', explode(',', $value));
        }

        if ( ! is_array($value) ) {
            $values[] = $value;
        } else {
            $values = array_merge($values, $value);
        }

        $this->headers[$this->names[$lowerName]] = array_unique($values);
    }

    /**
     * @param string            $name
     * @param string|string[]   $value
     */
    public function set(string $name, $value): void
    {
        if ( is_string($value) && strpos($value, ',') !== false ) {
            # if the value is comma separated, then make array from it to aware consistency
            $value = array_map('trim', explode(',', $value));
        }

        $lowerName = strtolower($name);

        if ( ! isset($this->names[$lowerName]) ) {
            $this->names[$lowerName] = $name;
        }

        $this->headers[$this->names[$lowerName]] = is_array($value) ? $value : [$value];
    }

    /**
     * @param string $name
     */
    public function remove(string $name): void
    {
        $lowerName = strtolower($name);

        if ( isset($this->names[$lowerName]) ) {
            unset($this->headers[$this->names[$lowerName]], $this->names[$lowerName]);
        }
    }

    #-------------------------------------------------------------------------------------------------------------------
    #
    #   Standard request fields wrapper
    #
    #-------------------------------------------------------------------------------------------------------------------

    /**
     * Media type(s) that is/are acceptable for the response.
     *
     * @return string
     */
    public function getAccept(): string
    {
        return $this->getLine('Accept');
    }

    /**
     * Character sets that are acceptable.
     *
     * @return string
     */
    public function getAcceptCharset(): string
    {
        return $this->getLine('Accept-Charset');
    }

    /**
     * Acceptable version in time.
     *
     * @return string
     */
    public function getAcceptDatetime(): string
    {
        return $this->getLine('Accept-Datetime');
    }

    /**
     * List of acceptable encodings.
     *
     * @return string
     */
    public function getAcceptEncoding(): string
    {
        return $this->getLine('Accept-Encoding');
    }

    /**
     * Initiates a request for cross-origin resource sharing with Origin
     *
     * @return string
     */
    public function getAccessControlRequestMethod(): string
    {
        return $this->getLine('Access-Control-Request-Method');
    }

    /**
     * Initiates a request for cross-origin resource sharing with Origin
     *
     * @return string
     */
    public function getAccessControlRequestHeaders(): string
    {
        return $this->getLine('Access-Control-Request-Headers');
    }

    /**
     * Authentication credentials for HTTP authentication.
     *
     * @return string
     */
    public function getAuthorization(): string
    {
        return $this->getLine('Authorization');
    }

    /**
     * 	Used to specify directives that must be obeyed by all caching mechanisms along the request-response chain.
     *
     * @return string
     */
    public function getCacheControl(): string
    {
        return $this->getLine('Cache-Control');
    }

    /**
     * Control options for the current connection and list of hop-by-hop request fields.
     * Must not be used with HTTP/2.
     *
     * @return string
     */
    public function getConnection(): string
    {
        return $this->getLine('Connection');
    }

    /**
     * Returns the value of the header Content-Type.
     *
     * @return string
     */
    public function getContentType(): string
    {
        return $this->getLine('content-type');
    }

    /**
     * Returns the value of the header Content-Type.
     *
     * @return string
     */
    public function getContentLength(): string
    {
        return $this->getLine('content-length');
    }

    /**
     * A Base64-encoded binary MD5 sum of the content of the request body.
     *
     * @return string
     */
    public function getContentMD5(): string
    {
        return $this->getLine('ContentMD5');
    }

    /**
     * 	An HTTP cookie previously sent by the server with Set-Cookie.
     *
     * @return string
     */
    public function getCookie(): string
    {
        return $this->getLine('Cookie');
    }

    /**
     * The date and time at which the message was originated
     * (in "HTTP-date" format as defined by RFC 7231 Date/Time Formats).
     *
     * @return string
     */
    public function getDate(): string
    {
        return $this->getLine('Date');
    }

    /**
     * Indicates that particular server behaviors are required by the client.
     *
     * @return string
     */
    public function getExpect(): string
    {
        return $this->getLine('Expect');
    }

    /**
     * 	Disclose original information of a client connecting to a web server through an HTTP proxy.
     *
     * @return string
     */
    public function getForwarded(): string
    {
        return $this->getLine('Forwarded');
    }

    /**
     * The email address of the user making the request.
     *
     * @return string
     */
    public function getFrom(): string
    {
        return $this->getLine('From');
    }

    /**
     * The domain name of the server (for virtual hosting), and the TCP port number on which the server is listening.
     * The port number may be omitted if the port is the standard port for the service requested. Mandatory since
     * HTTP/1.1. If the request is generated directly in HTTP/2, it should not be used.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->getLine('Host');
    }

    /**
     * A request that upgrades from HTTP/1.1 to HTTP/2 MUST include exactly one HTTP2-Setting header field.
     * The HTTP2-Settings header field is a connection-specific header field that includes parameters that govern
     * the HTTP/2 connection, provided in anticipation of the server accepting the request to upgrade.
     *
     * @return string
     */
    public function getHTTP2Settings(): string
    {
        return $this->getLine('HTTP2-Settings');
    }

    /**
     * Only perform the action if the client supplied entity matches the same entity on the server.
     * This is mainly for methods like PUT to only update a resource if it has not been
     * modified since the user last updated it.
     *
     * @return string
     */
    public function getIfMatch(): string
    {
        return $this->getLine('If-Match');
    }

    /**
     * Allows a 304 Not Modified to be returned if content is unchanged.
     *
     * @return string
     */
    public function getIfModifiedSince(): string
    {
        return $this->getLine('If-Modified-Since');
    }

    /**
     * Allows a 304 Not Modified to be returned if content is unchanged, see HTTP ETag.
     *
     * @return string
     */
    public function getIfNoneMatch(): string
    {
        return $this->getLine('If-None-Match');
    }

    /**
     * If the entity is unchanged, send me the part(s) that I am missing; otherwise, send me the entire new entity.
     *
     * @return string
     */
    public function getIfRange(): string
    {
        return $this->getLine('If-Range');
    }

    /**
     * Only send the response if the entity has not been modified since a specific time.
     *
     * @return string
     */
    public function getIfUnmodifiedSince(): string
    {
        return $this->getLine('If-Unmodified-Since');
    }

    /**
     * Limit the number of times the message can be forwarded through proxies or gateways.
     *
     * @return string
     */
    public function getMaxForwards(): string
    {
        return $this->getLine('Max-Forwards');
    }

    /**
     * Initiates a request for cross-origin resource sharing (asks server for Access-Control-* response fields).
     *
     * @return string
     */
    public function getOrigin(): string
    {
        return $this->getLine('Origin');
    }

    /**
     * Implementation-specific fields that may have various effects anywhere along the request-response chain.
     *
     * @return string
     */
    public function getPragma(): string
    {
        return $this->getLine('Pragma');
    }

    /**
     * Authorization credentials for connecting to a proxy.
     *
     * @return string
     */
    public function getProxyAuthorization(): string
    {
        return $this->getLine('Proxy-Authorization');
    }

    /**
     * Request only part of an entity. Bytes are numbered from 0. See Byte serving.
     *
     * @return string
     */
    public function getRange(): string
    {
        return $this->getLine('Range');
    }

    /**
     * This is the address of the previous web page from which a link to the currently requested page was followed.
     * (The word "referrer" has been misspelled in the RFC as well as in most implementations to the point that
     * it has become standard usage and is considered correct terminology)
     *
     * @return string
     */
    public function getReferer(): string
    {
        return $this->getLine('Referer');
    }

    /**
     * The transfer encodings the user agent is willing to accept: the same values as for the response header field
     * Transfer-Encoding can be used, plus the "trailers" value (related to the "chunked" transfer method) to
     * notify the server it expects to receive additional fields in the trailer after the last, zero-sized, chunk.
     * Only trailers is supported in HTTP/2.
     *
     * @return string
     */
    public function getTE(): string
    {
        return $this->getLine('TE');
    }

    /**
     * The Trailer general field value indicates that the given set of header fields is present in the trailer of a message encoded with chunked transfer coding.
     *
     * @return string
     */
    public function getTrailer(): string
    {
        return $this->getLine('Trailer');
    }

    /**
     * The form of encoding used to safely transfer the entity to the user.
     * Currently defined methods are: chunked, compress, deflate, gzip, identity.
     * Must not be used with HTTP/2.
     *
     * @return string
     */
    public function getTransferEncoding(): string
    {
        return $this->getLine('Transfer-Encoding');
    }

    /**
     * The user agent string of the user agent.
     *
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->getLine('User-Agent');
    }

    /**
     * Ask the server to upgrade to another protocol.
     * Must not be used in HTTP/2
     *
     * @return string
     */
    public function getUpgrade(): string
    {
        return $this->getLine('Upgrade');
    }

    /**
     * Informs the server of proxies through which the request was sent.
     *
     * @return string
     */
    public function getVia(): string
    {
        return $this->getLine('Via');
    }

    /**
     * A general warning about possible problems with the entity body.
     *
     * @return string
     */
    public function getWarning(): string
    {
        return $this->getLine('Warning');
    }

    #-------------------------------------------------------------------------------------------------------------------
    #
    #   Common non-standard request fields wrapper
    #
    #-------------------------------------------------------------------------------------------------------------------

    /**
     * Tells a server which (presumably in the middle of a HTTP -> HTTPS migration) hosts mixed content that the
     * client would prefer redirection to HTTPS and can handle Content-Security-Policy: upgrade-insecure-requests
     * Must not be used with HTTP/2.
     *
     * @return string
     */
    public function getUpgradeInsecureRequests(): string
    {
        return $this->getLine('Upgrade-Insecure-Requests');
    }

    /**
     * Mainly used to identify Ajax requests. Most JavaScript frameworks send this field with value of XMLHttpRequest.
     *
     * @return string
     */
    public function getXRequestedWith(): string
    {
        return $this->getLine('X-Requested-With');
    }

    /**
     * Requests a web application to disable their tracking of a user. This is Mozilla's version of the
     * X-Do-Not-Track header field (since Firefox 4.0 Beta 11).
     * Safari and IE9 also have support for this field.[22] On March 7, 2011, a draft proposal was submitted
     * to IETF. The W3C Tracking Protection Working Group is producing a specification
     *
     * @return string
     */
    public function getDNT(): string
    {
        return $this->getLine('X-Forwarded-For');
    }

    /**
     * A de facto standard for identifying the originating IP address of a client connecting to a web
     * server through an HTTP proxy or load balancer. Superseded by Forwarded header.
     *
     * @return string
     */
    public function getXForwardedFor(): string
    {
        return $this->getLine('X-Forwarded-For');
    }

    /**
     * A de facto standard for identifying the original host requested by the client in the Host HTTP request
     * header, since the host name and/or port of the reverse proxy (load balancer) may differ from the
     * origin server handling the request. Superseded by Forwarded header.
     *
     * @return string
     */
    public function getXForwardedHost(): string
    {
        return $this->getLine('X-Forwarded-Host');
    }

    /**
     * A de facto standard for identifying the originating protocol of an HTTP request, since a reverse proxy
     * (or a load balancer) may communicate with a web server using HTTP even if the request to the
     * reverse proxy is HTTPS. An alternative form of the header (X-ProxyUser-Ip) is used by Google clients
     * talking to Google servers. Superseded by Forwarded header.
     *
     * @return string
     */
    public function getXForwardedProto(): string
    {
        return $this->getLine('X-Forwarded-Proto');
    }

    /**
     * Non-standard header field used by Microsoft applications and load-balancers.
     *
     * @return string
     */
    public function getFrontEndHttps(): string
    {
        return $this->getLine('Front-End-Https');
    }

    /**
     * Requests a web application to override the method specified in the request (typically POST)
     * with the method given in the header field (typically PUT or DELETE). This can be used when a
     * user agent or firewall prevents PUT or DELETE methods from being sent directly (note that this
     * is either a bug in the software component, which ought to be fixed, or an intentional configuration,
     * in which case bypassing it may be the wrong thing to do).
     *
     * @return string
     */
    public function getXHttpMethodOverride(): string
    {
        return $this->getLine('X-Http-Method-Override');
    }

    /**
     * 	Allows easier parsing of the MakeModel/Firmware that is usually found in the User-Agent String of AT&T Devices.
     *
     * @return string
     */
    public function getXATTDeviceId(): string
    {
        return $this->getLine('X-ATT-DeviceId');
    }

    /**
     * Links to an XML file on the Internet with a full description and details about the device currently connecting.
     * In the example to the right is an XML file for an AT&T Samsung Galaxy S2.
     *
     * @return string
     */
    public function getXWapProfile(): string
    {
        return $this->getLine('X-Wap-Profile');
    }

    /**
     * Implemented as a misunderstanding of the HTTP specifications. Common because of mistakes in implementations
     * of early HTTP versions. Has exactly the same functionality as standard Connection field.
     * Must not be used with HTTP/2.
     *
     * @return string
     */
    public function getProxyConnection(): string
    {
        return $this->getLine('Proxy-Connection');
    }

    /**
     * Server-side deep packet insertion of a unique ID identifying customers of Verizon
     * Wireless; also known as "perma-cookie" or "supercookie"
     *
     * @return string
     */
    public function getXUIDH(): string
    {
        return $this->getLine('X-UIDH');
    }

    /**
     * Used to prevent cross-site request forgery. Alternative header names are: X-CSRFToken and X-XSRF-TOKEN
     *
     * @return string
     */
    public function getXCsrfToken(): string
    {
        return $this->getLine('X-Csrf-Token');
    }

    /**
     * Correlates HTTP requests between a client and server.
     *
     * @return string
     */
    public function getXRequestID(): string
    {
        return $this->getLine('X-Request-ID');
    }

    /**
     * Correlates HTTP requests between a client and server.
     *
     * @return string
     */
    public function getXCorrelationID(): string
    {
        return $this->getLine('X-Correlation-ID');
    }

    /**
     * The Save-Data client hint request header available in Chrome, Opera, and Yandex browsers lets developers
     * deliver lighter, faster applications to users who opt-in to data saving mode in their browser.
     *
     * @return string
     */
    public function getSaveData(): string
    {
        return $this->getLine('Save-Data');
    }

    /**
     * Returns an array that contains all header fields with its name as key and an array with values as value.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->headers;
    }

}
