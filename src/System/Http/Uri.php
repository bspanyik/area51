<?php

namespace Area51\System\Http;

use Area51\System\Collection;

class Uri
{
    const SCHEME_HTTPS = 'https';
    const SCHEME_HTTP = 'http';
    const PORT_HTTPS = 43;
    const PORT_HTTP = 80;

    /**
     * @var string
     */
    private $scheme;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $query;

    /**
     * @var string
     */
    private $fragment;

    /**
     * @param Collection $environment
     */
    public function __construct(Collection $environment)
    {
        $this->scheme   = $this->prepareScheme($environment);
        $this->user     = $this->prepareUser($environment);
        $this->password = $this->preparePassword($environment);
        $this->host     = $this->prepareHost($environment);
        $this->port     = $this->preparePort($environment);
        $this->path     = $this->preparePath($environment);
        $this->query    = $this->prepareQuery($environment);
        $this->fragment = $this->prepareFragment($environment);
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUserInfo(): string
    {
        return $this->user . ($this->password ? ':' . $this->password : '');
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port && !$this->hasStandardPort() ? $this->port : null;
    }

    /**
     * @return string
     */
    public function getAuthority(): string
    {
        $userInfo = $this->getUserInfo();
        $host = $this->getHost();
        $port = $this->getPort();

        return ($userInfo ? $userInfo . '@' : '') . $host . ($port !== null ? ':' . $port : '');
    }

    /**
     * @return mixed
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * @param Collection $environment
     * @return string
     */
    private function prepareScheme(Collection $environment): string
    {
        $isSecure = $environment->get('HTTPS');

        return (empty($isSecure) || $isSecure === 'off') ? 'http' : 'https';
    }

    /**
     * @param Collection $environment
     * @return string
     */
    private function prepareUser(Collection $environment): string
    {
        return $environment->get('PHP_AUTH_USER', '');
    }

    /**
     * @param Collection $environment
     * @return string
     */
    private function preparePassword(Collection $environment): string
    {
        return $environment->get('PHP_AUTH_PW', '');
    }

    /**
     * @param Collection $environment
     * @return string
     */
    private function prepareHost(Collection $environment): string
    {
        if ($environment->has('HTTP_HOST')) {
            $host = $environment->get('HTTP_HOST');
        } elseif ($environment->has('SERVER_NAME')) {
            $host = $environment->get('SERVER_NAME');
        } else {
            $host = $environment->get('SERVER_ADDR', '');
        }

        $host = strtolower(preg_replace('/:\d+$/', '', trim($host)));

        if ($host && '' === preg_replace('/(?:^\[)?[a-zA-Z0-9-:\]_]+\.?/', '', $host)) {
            return $host;
        }

        return '';
    }

    /**
     * @param Collection $environment
     * @return int
     */
    private function preparePort(Collection $environment): int
    {
        $serverPort = $environment->get('SERVER_PORT');
        if (is_numeric($serverPort)) {
            return (int) $serverPort;
        }

        return self::SCHEME_HTTPS === $this->scheme ? self::PORT_HTTPS : self::PORT_HTTP;
    }

    /**
     * @param Collection $environment
     * @return string
     */
    private function preparePath(Collection $environment): string
    {
        $requestUri = parse_url('http://example.com' . $environment->get('REQUEST_URI'), PHP_URL_PATH);

        $this->basePath = $this->prepareBasePath($environment, $requestUri);
        if ($this->basePath) {
            return ltrim(substr($requestUri, strlen($this->basePath)), '/');
        }

        return $requestUri;
    }

    /**
     * @param Collection $environment
     * @param string $requestUri
     * @return string
     */
    private function prepareBasePath(Collection $environment, string $requestUri): string
    {
        $requestScriptName = '/' . parse_url($environment->get('SCRIPT_FILENAME'), PHP_URL_PATH);
        $requestScriptDir = dirname($requestScriptName);

        if (stripos($requestUri, $requestScriptName) === 0) {
            return $requestScriptName;
        }

        if ($requestScriptDir !== '/' && stripos($requestUri, $requestScriptDir) === 0) {
            return $requestScriptDir;
        }

        return '';
    }

    /**
     * @param Collection $environment
     * @return string
     */
    private function prepareQuery(Collection $environment): string
    {
        $queryString = $environment->get('QUERY_STRING', '');
        if (!empty($queryString)) {
            return $queryString;
        }

        return (string) parse_url('http://example.com' . $environment->get('REQUEST_URI'), PHP_URL_QUERY);
    }

    /**
     * @param Collection $environment
     * @return string
     */
    private function prepareFragment(Collection $environment): string
    {
        return (string) parse_url('http://example.com' . $environment->get('REQUEST_URI'), PHP_URL_FRAGMENT);
    }

    /**
     * @return bool
     */
    private function hasStandardPort(): bool
    {
        return $this->hasStandardHttpPort() || $this->hasStandardHttpsPort();
    }

    /**
     * @return bool
     */
    private function hasStandardHttpPort(): bool
    {
        return self::SCHEME_HTTP === $this->scheme && self::PORT_HTTP === $this->port;
    }

    /**
     * @return bool
     */
    private function hasStandardHttpsPort(): bool
    {
        return self::SCHEME_HTTPS === $this->scheme && self::PORT_HTTPS === $this->port;
    }

}
