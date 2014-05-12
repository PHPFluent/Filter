<?php

namespace PHPFluent\Filter;

/**
 * A filter builder
 */
class Builder
{

    protected $factory;
    protected $filters = array();
    protected static $defaultFactory;

    public function __construct(Factory $factory = null)
    {
        $this->factory = $factory ?: static::getDefaultFactory();
    }

    public function getFactory()
    {
        return $this->factory;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public static function setDefaultFactory(Factory $factory)
    {
        static::$defaultFactory = $factory;
    }

    public static function getDefaultFactory()
    {
        if (! static::$defaultFactory instanceof Factory) {
            static::$defaultFactory = new Factory();
        }

        return static::$defaultFactory;
    }

    public function filter($value)
    {
        $filteredValue = $value;
        foreach ($this->getFilters() as $filter) {
            $filteredValue = $filter->filter($filteredValue);
        }

        return $filteredValue;
    }

    public static function __callStatic($methodName, array $arguments = array())
    {
        $builder = new static(static::getDefaultFactory());
        $builder->__call($methodName, $arguments);

        return $builder;
    }

    public function __call($methodName, array $arguments = array())
    {
        $this->filters[] = $this->factory->filter($methodName, $arguments);

        return $this;
    }

    public function __invoke($value)
    {
        return $this->filter($value);
    }
}
