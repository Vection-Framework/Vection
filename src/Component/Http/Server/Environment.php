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

namespace Vection\Component\Http\Server;

/**
 * Class Environment
 *
 * @package Vection\Component\Http\Server
 */
class Environment
{
    /** @var array */
    protected $data;

    /**
     * Environment constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = array_change_key_case($data ?: $_SERVER, CASE_UPPER);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ? string
    {
        return $this->data[$key] ?? null;
    }

    /**
     * The filename of the currently executing script, relative to the document root. For instance, $_SERVER['PHP_SELF']
     * in a script at the address http://example.com/foo/bar.php would be /foo/bar.php. The __FILE__ constant contains
     * the full path and filename of the current (i.e. included) file. If PHP is running as a command-line processor
     * this variable contains the script name since PHP 4.3.0. Previously it was not available.
     *
     * @return string
     */
    public function getSelf(): string
    {
        return $this->data['PHP_SELF'] ?? '';
    }

    /**
     * Array of arguments passed to the script. When the script is run on the command line, this gives C-style access
     * to the command line parameters. When called via the GET method, this will contain the query string.
     *
     * @return string
     */
    public function getArgV(): string
    {
        return $this->data['ARGV'] ?? '';
    }

    /**
     * Contains the number of command line parameters passed to the script (if run on the command line).
     *
     * @return string
     */
    public function getArgC(): string
    {
        return $this->data['ARGC'] ?? '';
    }

    /**
     * What revision of the CGI specification the server is using; e.g. 'CGI/1.1'.
     *
     * @return string
     */
    public function getGatewayInterface(): string
    {
        return $this->data['GATEWAY_INTERFACE'] ?? '';
    }

    /**
     * The IP address of the server under which the current script is executing.
     *
     * @return string
     */
    public function getServerAddr(): string
    {
        return $this->data['SERVER_ADDR'] ?? '';
    }

    /**
     * The name of the server host under which the current script is executing. If the script is running on a virtual
     * host, this will be the value defined for that virtual host.
     *
     * Note: Under Apache 2, you must set UseCanonicalName = On and ServerName. Otherwise, this value reflects the
     * hostname supplied by the client, which can be spoofed. It is not safe to rely on this value in security-dependent
     * contexts.
     *
     * @return string
     */
    public function getServerName(): string
    {
        return $this->data['SERVER_NAME'] ?? '';
    }

    /**
     * Server identification string, given in the headers when responding to requests.
     *
     * @return string
     */
    public function getServerSoftware(): string
    {
        return $this->data['SERVER_SOFTWARE'] ?? '';
    }

    /**
     * Name and revision of the information protocol via which the page was requested; e.g. 'HTTP/1.0';
     *
     * @return string
     */
    public function getServerProtocol(): string
    {
        return $this->data['SERVER_PROTOCOL'] ?? '';
    }

    /**
     * Which request method was used to access the page; e.g. 'GET', 'HEAD', 'POST', 'PUT'.
     *
     * Note: PHP script is terminated after sending headers (it means after producing any output without output
     * buffering) if the request method was HEAD.
     *
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->data['REQUEST_METHOD'] ?? '';
    }

    /**
     * The timestamp of the start of the request. Available since PHP 5.1.0.
     *
     * @return string
     */
    public function getRequestTime(): string
    {
        return $this->data['REQUEST_TIME'] ?? '';
    }

    /**
     * The timestamp of the start of the request, with microsecond precision. Available since PHP 5.4.0.
     *
     * @return string
     */
    public function getRequestTimeFloat(): string
    {
        return $this->data['REQUEST_TIME_FLOAT'] ?? '';
    }

    /**
     * The query string, if any, via which the page was accessed.
     *
     * @return string
     */
    public function getQueryString(): string
    {
        return $this->data['QUERY_STRING'] ?? '';
    }

    /**
     * The document root directory under which the current script is executing, as defined in the server's
     * configuration file.
     *
     * @return string
     */
    public function getDocumentRoot(): string
    {
        return $this->data['DOCUMENT_ROOT'] ?? '';
    }

    /**
     * Contents of the Accept: header from the current request, if there is one.
     *
     * @return string
     */
    public function getHttpAccept(): string
    {
        return $this->data['HTTP_ACCEPT'] ?? '';
    }

    /**
     * Contents of the Accept-Charset: header from the current request, if there is one. Example: 'iso-8859-1,*,utf-8'.
     *
     * @return string
     */
    public function getHttpAcceptCharset(): string
    {
        return $this->data['HTTP_ACCEPT_CHARSET'] ?? '';
    }

    /**
     * Contents of the Accept-Encoding: header from the current request, if there is one. Example: 'gzip'.
     *
     * @return string
     */
    public function getHttpAcceptEncoding(): string
    {
        return $this->data['HTTP_ACCEPT_ENCODING'] ?? '';
    }

    /**
     * Contents of the Accept-Language: header from the current request, if there is one. Example: 'en'.
     *
     * @return string
     */
    public function getHttpAcceptLanguage(): string
    {
        return $this->data['HTTP_ACCEPT_LANGUAGE'] ?? '';
    }

    /**
     * Contents of the Connection: header from the current request, if there is one. Example: 'Keep-Alive'.
     *
     * @return string
     */
    public function getHttpConnection(): string
    {
        return $this->data['HTTP_CONNECTION'] ?? '';
    }

    /**
     * Contents of the Host: header from the current request, if there is one.
     *
     * @return string
     */
    public function getHttpHost(): string
    {
        return $this->data['HTTP_HOST'] ?? '';
    }

    /**
     * The address of the page (if any) which referred the user agent to the current page. This is set by the user
     * agent. Not all user agents will set this, and some provide the ability to modify HTTP_REFERER as a feature.
     * In short, it cannot really be trusted.
     *
     * @return string
     */
    public function getHttpReferer(): string
    {
        return $this->data['HTTP_REFERER'] ?? '';
    }

    /**
     * Contents of the User-Agent: header from the current request, if there is one. This is a string denoting the user
     * agent being which is accessing the page. A typical example is: Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). Among
     * other things, you can use this value with get_browser() to tailor your page's output to the capabilities of the
     * user agent.
     *
     * @return string
     */
    public function getHttpUserAgent(): string
    {
        return $this->data['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * Set to a non-empty value if the script was queried through the HTTPS protocol.
     *
     * @return string
     */
    public function getHttps(): string
    {
        return $this->data['HTTPS'] ?? '';
    }

    /**
     * The IP address from which the user is viewing the current page.
     *
     * @return string
     */
    public function getRemoteAddr(): string
    {
        return $this->data['REMOTE_ADDR'] ?? '';
    }

    /**
     * The Host name from which the user is viewing the current page. The reverse dns lookup is based on the
     * REMOTE_ADDR of the user.
     *
     * Note: Your web server must be configured to create this variable. For example in Apache you'll need
     * HostnameLookups On inside httpd.conf for it to exist. See also gethostbyaddr().
     *
     * @return string
     */
    public function getRemoteHost(): string
    {
        return $this->data['REMOTE_HOST'] ?? '';
    }

    /**
     * The port being used on the user's machine to communicate with the web server.
     *
     * @return string
     */
    public function getRemotePort(): string
    {
        return $this->data['REMOTE_PORT'] ?? '';
    }

    /**
     * The authenticated user.
     *
     * @return string
     */
    public function getRemoteUser(): string
    {
        return $this->data['REMOTE_USER'] ?? '';
    }

    /**
     * The authenticated user if the request is internally redirected.
     *
     * @return string
     */
    public function getRedirectRemoteUser(): string
    {
        return $this->data['REDIRECT_REMOTE_USER'] ?? '';
    }

    /**
     * The absolute pathname of the currently executing script.
     *
     * Note: If a script is executed with the CLI, as a relative path, such as file.php or ../file.php,
     * $_SERVER['SCRIPT_FILENAME'] will contain the relative path specified by the user.
     *
     * @return string
     */
    public function getScriptFilename(): string
    {
        return $this->data['SCRIPT_FILENAME'] ?? '';
    }

    /**
     * The value given to the SERVER_ADMIN (for Apache) directive in the web server configuration file.
     * If the script is running on a virtual host, this will be the value defined for that virtual host.
     *
     * @return string
     */
    public function getServerAdmin(): string
    {
        return $this->data['SERVER_ADMIN'] ?? '';
    }

    /**
     * The port on the server machine being used by the web server for communication. For default setups,
     * this will be '80'; using SSL, for instance, will change this to whatever your defined secure HTTP port is.
     *
     * Note: Under the Apache 2, you must set UseCanonicalName = On, as well as UseCanonicalPhysicalPort = On in order
     * to get the physical (real) port, otherwise, this value can be spoofed and it may or may not return the physical
     * port value. It is not safe to rely on this value in security-dependent contexts.
     *
     * @return string
     */
    public function getServerPort(): string
    {
        return $this->data['SERVER_PORT'] ?? '';
    }

    /**
     * String containing the server version and virtual host name which are added to server-generated pages, if enabled.
     *
     * @return string
     */
    public function getServerSignature(): string
    {
        return $this->data['SERVER_SIGNATURE'] ?? '';
    }

    /**
     * Filesystem- (not document root-) based path to the current script, after the server has done any virtual-to-real
     * mapping.
     *
     * Note: As of PHP 4.3.2, PATH_TRANSLATED is no longer set implicitly under the Apache 2 SAPI in contrast to the
     * situation in Apache 1, where it's set to the same value as the SCRIPT_FILENAME server variable when it's not
     * populated by Apache. This change was made to comply with the CGI specification that PATH_TRANSLATED should only
     * exist if PATH_INFO is defined. Apache 2 users may use AcceptPathInfo = On inside httpd.conf to define PATH_INFO.
     *
     * @return string
     */
    public function getPathTranslated(): string
    {
        return $this->data['PATH_TRANSLATED'] ?? '';
    }

    /**
     * Contains the current script's path. This is useful for pages which need to point to themselves.
     * The __FILE__ constant contains the full path and filename of the current (i.e. included) file.
     *
     * @return string
     */
    public function getScriptName(): string
    {
        return $this->data['SCRIPT_NAME'] ?? '';
    }

    /**
     * The URI which was given in order to access this page; for instance, '/index.html'.
     *
     * @return string
     */
    public function getRequestUri(): string
    {
        return $this->data['REQUEST_URI'] ?? '';
    }

    /**
     * When doing Digest HTTP authentication this variable is set to the 'Authorization' header sent by the client
     * (which you should then use to make the appropriate validation).
     *
     * @return string
     */
    public function getPHPAuthDigest(): string
    {
        return $this->data['PHP_AUTH_DIGEST'] ?? '';
    }

    /**
     * When doing HTTP authentication this variable is set to the username provided by the user.
     *
     * @return string
     */
    public function getPHPAuthUser(): string
    {
        return $this->data['PHP_AUTH_USER'] ?? '';
    }

    /**
     * When doing HTTP authentication this variable is set to the password provided by the user.
     *
     * @return string
     */
    public function getPHPAuthPW(): string
    {
        return $this->data['PHP_AUTH_PW'] ?? '';
    }

    /**
     * When doing HTTP authentication this variable is set to the authentication type.
     *
     * @return string
     */
    public function getAuthType(): string
    {
        return $this->data['AUTH_TYPE'] ?? '';
    }

    /**
     * Contains any client-provided pathname information trailing the actual script filename but preceding the query
     * string, if available. For instance, if the current script was accessed via the URL
     * http://www.example.com/php/path_info.php/some/stuff?foo=bar, then $_SERVER['PATH_INFO']
     * would contain /some/stuff.
     *
     * @return string
     */
    public function getPathInfo(): string
    {
        return $this->data['PATH_INFO'] ?? '';
    }

    /**
     * Original version of 'PATH_INFO' before processed by PHP.
     *
     * @return string
     */
    public function getOrigPathInfo(): string
    {
        return $this->data['ORIG_PATH_INFO'] ?? '';
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}