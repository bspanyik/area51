<?php

namespace Area51\System;

class Container
{
    /** @var callable[] */
    private $definitions;

    /** @var array */
    private $resolvedEntries = [];

    /** @var array */
    private $entriesBeingResolved = [];

    /**
     * @param array $definitions
     */
    public function __construct(array $definitions = []) {
        $this->definitions = $definitions;
        $this->resolvedEntries = [
            self::class => $this,
        ];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name): bool
    {
        return $this->hasResolved($name) || $this->hasDefinition($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function add(string $name, $value)
    {
        if (!$this->has($name)) {
            $this->resolvedEntries[$name] = $value;
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get(string $name)
    {
        if ($this->hasResolved($name)) {
            return $this->resolvedEntries[$name];
        }

        $definition = $this->getDefinition($name);
        $value = $this->resolveDefinition($name, $definition);
        $this->resolvedEntries[$name] = $value;

        return $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    private function hasResolved(string $name): bool
    {
        return array_key_exists($name, $this->resolvedEntries);
    }

    /**
     * @param string $name
     * @return bool
     */
    private function hasDefinition(string $name): bool
    {
        return array_key_exists($name, $this->definitions);
    }

    /**
     * @param $name
     * @return callable
     * @throws \OutOfBoundsException
     */
    private function getDefinition($name)
    {
        if (!$this->hasDefinition($name)) {
            throw new \OutOfBoundsException(sprintf('No definitions were found in the container for name: %s.', $name));
        }

        return $this->definitions[$name];
    }

    /**
     * @param string $entryName
     * @param mixed $definition
     * @return mixed
     * @throws \UnexpectedValueException
     */
    private function resolveDefinition($entryName, $definition)
    {
        if (isset($this->entriesBeingResolved[$entryName])) {
            throw new \UnexpectedValueException(sprintf('Circular dependency detected while trying to resolve entry %s', $entryName));
        }

        $this->entriesBeingResolved[$entryName] = true;

        try {
            if (is_callable($definition)) {
                $value = call_user_func($definition, $this);
            } else {
                $value = $definition;
            }
        } finally {
            unset($this->entriesBeingResolved[$entryName]);
        }

        return $value;
    }

}
