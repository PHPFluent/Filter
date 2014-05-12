<?php

namespace PHPFluent\Filter;

/**
 * @covers PHPFluent\Filter\Factory
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldHaveZendFilterAsDefaultPrefix()
    {
        $factory = new Factory();
        $prefixes = $factory->getPrefixes();

        $this->assertEquals('Zend\\Filter\\', $prefixes[0]);
    }

    public function testShouldHaveZendFilterFileAsDefaultPrefix()
    {
        $factory = new Factory();
        $prefixes = $factory->getPrefixes();

        $this->assertEquals('Zend\\Filter\\File\\', $prefixes[1]);
    }

    public function testShouldHaveZendFilterWordAsDefaultPrefix()
    {
        $factory = new Factory();
        $prefixes = $factory->getPrefixes();

        $this->assertEquals('Zend\\Filter\\Word\\', $prefixes[2]);
    }

    public function testShouldBeAbleToAppendANewPrefix()
    {
        $factory = new Factory();
        $factory->appendPrefix('PHPFluent\\Filter\\');
        $prefixes = $factory->getPrefixes();

        $this->assertEquals('PHPFluent\\Filter\\', $prefixes[3]);
    }

    public function testShouldBeAbleToPrependANewPrefix()
    {
        $factory = new Factory();
        $factory->prependPrefix('PHPFluent\\Filter\\');
        $prefixes = $factory->getPrefixes();

        $this->assertEquals('PHPFluent\\Filter\\', $prefixes[0]);
    }

    public function testShouldCreateAZendFilterByName()
    {
        $factory = new Factory();

        $this->assertInstanceOf('Zend\Filter\StringToUpper', $factory->filter('stringToUpper'));
    }

    public function testShouldDefineConstructorArgumentsWhenCreatingAFilter()
    {
        $factory = new Factory();
        $filter = $factory->filter('stringTrim', array('aeiou'));

        $this->assertEquals('aeiou', $filter->getCharList());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage "uterere" is not a valid filter name
     */
    public function testShouldThrowsAnExceptionWhenFilterNameIsNotValid()
    {
        $factory = new Factory();
        $factory->filter('uterere');
    }

    /**
     * @expectedException UnexpectedValueException
     * @expectedExceptionMessage "PHPFluent\Filter\TestNonZendFilter" is not a valid filter
     */
    public function testShouldThrowsAnExceptionWhenFilterIsNotInstanceOfZendFilterInterface()
    {
        $factory = new Factory();
        $factory->appendPrefix('PHPFluent\\Filter\\Test');
        $factory->filter('nonZendFilter');
    }

    public function testShouldFactoryWhenFilterNameIsACallback()
    {
        $factory = new Factory();

        $this->assertInstanceOf('Zend\Filter\Callback', $factory->filter('ucwords'));
    }

    public function testShouldDefineCallbackNameWhenFilterNameIsACallback()
    {
        $factory = new Factory();
        $callback = $factory->filter('json_encode');

        $this->assertSame('json_encode', $callback->getCallback());
    }

    public function testShouldDefineParamsWhenFilterNameIsACallback()
    {
        $factory = new Factory();
        $callback = $factory->filter('json_encode', array(JSON_ERROR_SYNTAX));

        $this->assertSame(array(JSON_ERROR_SYNTAX), $callback->getCallbackParams());
    }
}


class TestNonZendFilter
{}
