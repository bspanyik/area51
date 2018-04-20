<?php

namespace Area51\System\Routing;

class Context
{
    const DEFAULT_METHOD = 'GET';

    /** @var string */
    private $scheme;

    /** @var string */
    private $httpHost;

    /** @var string */
    private $requestUri;

    /** @var string */
    private $baseUrl;

    /** @var string */
    private $path;

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? self::DEFAULT_METHOD;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        if (!isset($this->path)) {
            $this->path = $this->preparePath();
        }

        return $this->path;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        $payload = json_decode(file_get_contents('php://input'), true);
        if (false === $payload || null === $payload) {
            return [];
        }

        return $payload;
    }

    /**
     * @return string
     */
    private function preparePath(): string
    {
        if (null === ($requestUri = $this->getRequestUri())) {
            return '/';
        }

        if (false !== $pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        if ('' !== $requestUri && '/' !== $requestUri[0]) {
            $requestUri = '/' . $requestUri;
        }

        if (null === ($baseUrl = $this->getBaseUrl())) {
            return $requestUri;
        }

        $path = substr($requestUri, strlen($baseUrl));
        if (false === $path || '' === $path) {
            return '/';
        }

        return (string) $path;
    }

    /**
     * @return string
     */
    private function getRequestUri(): string
    {
        if (!isset($this->requestUri)) {
            $this->requestUri = $this->prepareRequestUri();
        }

        return $this->requestUri;
    }

    /**
     * @return string
     */
    private function prepareRequestUri(): string
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        $schemeAndHttpHost = $this->getSchemeAndHttpHost();

        if (0 === strpos($requestUri, $schemeAndHttpHost)) {
            $requestUri = substr($requestUri, strlen($schemeAndHttpHost));
        }

        $_SERVER['REQUEST_URI'] = $requestUri;

        return $requestUri;
    }

    /**
     * @return string
     */
    private function getSchemeAndHttpHost(): string
    {
        return $this->getScheme() . '://' . $this->getHttpHost();
    }

    /**
     * @return string
     */
    private function getScheme(): string
    {
        if (!isset($this->scheme)) {
            $this->scheme = $this->prepareScheme();
        }

        return $this->scheme;
    }

    /**
     * @return string
     */
    private function prepareScheme(): string
    {
        if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return 'https';
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
            return 'https';
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) !== 'off') {
            return 'https';
        }

        return 'http';
    }

    /**
     * @return string
     */
    private function getHttpHost(): string
    {
        if (!isset($this->httpHost)) {
            $this->httpHost = $this->prepareHttpHost();
        }

        return $this->httpHost;
    }

    /**
     * @return string
     */
    private function prepareHttpHost(): string
    {
        $scheme = $this->getScheme();
        $port = $this->getPort();

        if ($this->isStandardRequest($scheme, $port)) {
            return $this->prepareHost();
        }

        return $this->prepareHost() . ':' . $port;
    }

    /**
     * @return int
     */
    private function getPort(): int
    {
        if (isset($_SERVER['SERVER_PORT']) && is_numeric($_SERVER['SERVER_PORT'])) {
            return (int) $_SERVER['SERVER_PORT'];
        }

        return 'https' === $this->getScheme() ? 443 : 80;
    }

    /**
     * @param string $scheme
     * @param int $port
     * @return bool
     */
    private function isStandardRequest(string $scheme, int $port): bool
    {
        return $this->isStandardHttpRequest($scheme, $port) || $this->isStandardHttpsRequest($scheme, $port);
    }

    /**
     * @param string $scheme
     * @param int $port
     * @return bool
     */
    private function isStandardHttpRequest(string $scheme, int $port): bool
    {
        return 'http' === $scheme && 80 === $port;
    }

    /**
     * @param string $scheme
     * @param int $port
     * @return bool
     */
    private function isStandardHttpsRequest(string $scheme, int $port): bool
    {
        return 'https' === $scheme && 443 === $port;
    }

    /**
     * @return string
     */
    private function prepareHost(): string
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } elseif (isset($_SERVER['SERVER_NAME'])) {
            $host = $_SERVER['SERVER_NAME'];
        } else {
            $host = $_SERVER['SERVER_ADDR'] ?? '';
        }

        $host = strtolower(preg_replace('/:\d+$/', '', trim($host)));

        if ($host && '' === preg_replace('/(?:^\[)?[a-zA-Z0-9-:\]_]+\.?/', '', $host)) {
            return $host;
        }

        return '';
    }

    /**
     * @return string
     */
    private function getBaseUrl(): string
    {
        if (!isset($this->baseUrl)) {
            $this->baseUrl = $this->prepareBaseUrl();
        }

        return $this->baseUrl;
    }

    /**
     * @return string
     */
    private function prepareBaseUrl(): string
    {
        $baseUrl = $this->getRawBaseUrl();

        $requestUri = $this->getRequestUri();
        if ('' !== $requestUri && '/' !== $requestUri[0]) {
            $requestUri = '/' . $requestUri;
        }

        if ($baseUrl && false !== ($prefix = $this->getUrlencodedPrefix($requestUri, $baseUrl))) {
            // full $baseUrl matches
            return $prefix;
        }

        if ($baseUrl && false !== ($prefix = $this->getUrlencodedPrefix($requestUri, rtrim(dirname($baseUrl), '/' . DIRECTORY_SEPARATOR) . '/'))) {
            // directory portion of $baseUrl matches
            return rtrim($prefix, '/' . DIRECTORY_SEPARATOR);
        }

        $truncatedRequestUri = $requestUri;
        if (false !== $pos = strpos($requestUri, '?')) {
            $truncatedRequestUri = substr($requestUri, 0, $pos);
        }

        $basename = basename($baseUrl);
        if (empty($basename) || !strpos(rawurldecode($truncatedRequestUri), $basename)) {
            // no match whatsoever; set it blank
            return '';
        }

        // If using mod_rewrite or ISAPI_Rewrite strip the script filename
        // out of baseUrl. $pos !== 0 makes sure it is not matching a value
        // from PATH_INFO or QUERY_STRING
        if (strlen($requestUri) >= strlen($baseUrl) && (false !== $pos = strpos($requestUri, $baseUrl)) && 0 !== $pos) {
            $baseUrl = substr($requestUri, 0, $pos + strlen($baseUrl));
        }

        return rtrim($baseUrl, '/' . DIRECTORY_SEPARATOR);
    }

    /**
     * @return string
     */
    private function getRawBaseUrl(): string
    {
        $path = $_SERVER['PHP_SELF']        ?? '';
        $file = $_SERVER['SCRIPT_FILENAME'] ?? '';

        $segments = array_reverse(explode('/', trim($file, '/')));
        $last = count($segments);
        $index = 0;
        $baseUrl = '';
        do {
            $segment = $segments[$index];
            $baseUrl = '/' . $segment . $baseUrl;
            ++$index;
        } while ($last > $index && (false !== $pos = strpos($path, $baseUrl)) && 0 != $pos);

        return $baseUrl;
    }

    /**
     * Returns the prefix as encoded in the string when the string starts with
     * the given prefix, false otherwise.
     *
     * @param string $string
     * @param string $prefix
     *
     * @return string|false The prefix as it is encoded in $string, or false
     */
    private function getUrlencodedPrefix(string $string, string $prefix)
    {
        if (0 !== strpos(rawurldecode($string), $prefix)) {
            return false;
        }

        $len = strlen($prefix);

        if (preg_match(sprintf('#^(%%[[:xdigit:]]{2}|.){%d}#', $len), $string, $match)) {
            return $match[0];
        }

        return false;
    }

}
