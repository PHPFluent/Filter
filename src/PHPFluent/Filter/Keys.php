<?php

namespace PHPFluent\Filter;

class Keys implements FilterInterface
{
    protected $keys = array();

    public function __construct(array $keys = array())
    {
        foreach ($keys as $key => $filter) {
            $this->addKey($key, $filter);
        }
    }

    public function addKey($key, FilterInterface $filter)
    {
        $this->keys[$key] = $filter;

        return $this;
    }

    public function filter($input)
    {
        $filtered = array();
        foreach ($this->keys as $key => $filter) {
            if (!isset($input[$key])) {
                continue;
            }

            $filtered[$key] = $filter->filter($input[$key]);
        }

        return $filtered;
    }
}
