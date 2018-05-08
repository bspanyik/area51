<?php

namespace Area51\System\Routing;

class Context
{
    /** @var string */
    private $requestMethod;

    /** @var string */
    private $requestPath;

    /**
     * @param string $requestMethod
     * @param string $requestPath
     */
    public function __construct(string $requestMethod, string $requestPath)
    {
        $this->requestMethod = $requestMethod;
        $this->requestPath = $requestPath;
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    /**
     * @return string
     */
    public function getRequestPath(): string
    {
        return $this->requestPath;
    }

}
