<?php

namespace PHPFluent\Filter;

use InvalidArgumentException;
use ReflectionClass;
use UnexpectedValueException;

/**
 * A filter factory
 */
class Factory
{
    protected $prefixes = array(
        'Zend\\Filter\\',
        'Zend\\Filter\\File\\',
        'Zend\\Filter\\Word\\',
        'PHPFluent\\Filter\\',
    );

    public function getPrefixes()
    {
        return $this->prefixes;
    }

    public function appendPrefix($prefix)
    {
        array_push($this->prefixes, $prefix);
    }

    public function prependPrefix($prefix)
    {
        array_unshift($this->prefixes, $prefix);
    }

    public function filter($filterName, array $options = array())
    {
        foreach ($this->getPrefixes() as $prefix) {

            if (is_callable($filterName)) {
                $className = 'Zend\\Filter\\Callback';
                array_unshift($options, $filterName);
            } else {
                $className = $prefix . ucfirst($filterName);
            }

            if (! class_exists($className)) {
                continue;
            }

            $reflection = new ReflectionClass($className);
            if (! $reflection->isSubclassOf('Zend\Filter\FilterInterface')) {
                throw new UnexpectedValueException(sprintf('"%s" is not a valid filter', $className));
            }

            return $reflection->newInstanceArgs($options);
        }

        throw new InvalidArgumentException(sprintf('"%s" is not a valid filter name', $filterName));
    }
}
