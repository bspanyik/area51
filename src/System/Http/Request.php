<?php

namespace Area51\System\Http;

use Area51\System\Collection;

class Request
{
    /**
     * @var Collection
     */
    private $environment;

    /**
     * @var string
     */
    private $method;

    /**
     * @var Uri
     */
    private $uri;

    /**
     * @var Collection
     */
    private $headers;

    /**
     * @var Collection
     */
    private $query;

    /**
     * @var Collection
     */
    private $request;

    /**
     * @param Collection $environment
     */
    public function __construct(Collection $environment)
    {
        $this->environment = $environment;

        $this->method = $this->initMethod();
        $this->uri = $this->createUri();
        $this->headers = $this->createHeaders();
        $this->query = $this->createQuery();
        $this->request = $this->createRequest();
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->uri->getPath();
    }

    /**
     * @return string|null
     */
    public function getContentType()
    {
        return $this->headers->get('CONTENT_TYPE', null);
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function getParam($key, $default = null)
    {
        if ($this->request->has($key)) {
            return $this->request->get($key);
        }

        if ($this->query->has($key)) {
            return $this->query->get($key);
        }

        return $default;
    }

    /**
     * @return string
     */
    private function initMethod(): string
    {
        return $this->environment->get('REQUEST_METHOD', '');
    }

    /**
     * @return Uri
     */
    private function createUri(): Uri
    {
        return new Uri($this->environment);
    }

    /**
     * @return Collection
     */
    private function createHeaders(): Collection
    {
        $special = [
            'CONTENT_TYPE' => 1,
            'CONTENT_LENGTH' => 1,
            'PHP_AUTH_USER' => 1,
            'PHP_AUTH_PW' => 1,
            'PHP_AUTH_DIGEST' => 1,
            'AUTH_TYPE' => 1,
        ];

        $data = [];
        foreach ($this->environment->all() as $key => $value) {
            $key = strtoupper($key);
            if (isset($special[$key]) || strpos($key, 'HTTP_') === 0) {
                if ($key !== 'HTTP_CONTENT_LENGTH') {
                    $data[$key] =  $value;
                }
            }
        }

        return new Collection($data);
    }

    /**
     * @return Collection
     */
    private function createQuery(): Collection
    {
        parse_str($this->uri->getQuery(), $result);

        return new Collection($result);
    }

    /**
     * @return Collection
     */
    private function createRequest(): Collection
    {
        $body = file_get_contents('php://input');

        if ('application/json' === $this->getContentType()) {
            $result = json_decode($body, true);
        } else {
            $result = parse_str($body, $result);
        }

        if (!is_array($result)) {
            $result = [];
        }

        return new Collection($result);
    }

}
