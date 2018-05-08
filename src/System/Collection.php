<?php

namespace Area51\System;

class Collection
{
     /** @var array */
    protected $data = [];

    /**
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->clear();
        $this->replace($items);
    }

    /**
     * @param array $items
     */
    public function replace(array $items)
    {
        $this->data = array_replace($this->data, $items);
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param string
     * @param mixed
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    /**
     * @param string $key The data key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->data);
    }

    /**
     * Remove item from collection
     *
     * @param string $key The data key
     */
    public function remove($key)
    {
        unset($this->data[$key]);
    }

    /**
     * Remove all items from collection
     */
    public function clear()
    {
        $this->data = [];
    }
}
