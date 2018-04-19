<?php

namespace Area51\System\Routing;

class Route
{
    const REGEXP_PATTERN = "#^%s$#s";

    /** @var string */
    private $method;

    /** @var string */
    private $pattern;

    /** @var array */
    private $controller;

    /** @var array */
    private $parameters;

    public function __construct(string $method, string $pattern, array $controller, array $parameters = [])
    {
        $this->method     = $method;
        $this->pattern    = $pattern;
        $this->controller = $controller;
        $this->parameters = $parameters;
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
    public function getPattern(): string
    {
        return sprintf(self::REGEXP_PATTERN, $this->pattern);
    }

    /**
     * @return array
     */
    public function getController(): array
    {
        return $this->controller;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return bool
     */
    public function hasParameters(): bool
    {
        return !empty($this->parameters);
    }

}
