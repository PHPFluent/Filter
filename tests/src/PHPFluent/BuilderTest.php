<?php

namespace PHPFluent\Filter;

use Zend\Filter\Callback;
use Zend\Filter\StringToUpper;

/**
 * @covers PHPFluent\Filter\Builder
 */
class BuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $defaultFactory;

    protected function setUp()
    {
        $this->defaultFactory = Builder::getDefaultFactory();
    }

    protected function tearDown()
    {
        Builder::setDefaultFactory($this->defaultFactory);
    }

    protected function factory()
    {
        return $this
            ->getMockBuilder('PHPFluent\Filter\Factory')
            ->setMethods(array('filter'))
            ->getMock();
    }

    public function testShouldHaveAnInstanceOfFactoryByDefault()
    {
        $builder = new Builder();

        $this->assertInstanceOf('PHPFluent\Filter\Factory', $builder->getFactory());
    }

    public function testShouldAcceptAnInstanceOfFactoryOnConstructor()
    {
        $factory = new Factory();
        $builder = new Builder($factory);

        $this->assertSame($factory, $builder->getFactory());
    }

    public function testShouldUseDefaultInstanceOfFactoryWhenNoFactoryIsGiven()
    {
        $factory = new Factory();
        Builder::setDefaultFactory($factory);
        $builder = new Builder();

        $this->assertSame($factory, $builder->getFactory());
    }

    public function testShouldCallFactoryWhenCallingANonExistingMethod()
    {
        $factory = $this->factory();
        $factory
            ->expects($this->once())
            ->method('filter')
            ->with('trim');

        $builder = new Builder($factory);
        $builder->trim();
    }

    public function testShouldAddCalledFiltersToStack()
    {
        $trim = new Callback('trim');
        $ucwords = new Callback('ucwords');

        $factory = $this->factory();
        $factory
            ->expects($this->any())
            ->method('filter')
            ->will($this->onConsecutiveCalls($trim, $ucwords));

        $builder = new Builder($factory);
        $builder->trim();
        $builder->ucwords();

        $this->assertSame(array($trim, $ucwords), $builder->getFilters());
    }

    public function testShouldReturnTheBuilderInstanceWhenCallingANonExistingMethod()
    {
        $builder = new Builder($this->factory());

        $this->assertSame($builder, $builder->trim());
    }

    public function testShouldCallDefaultFactoryWhenCallingANonExistingMethodStatically()
    {
        $factory = $this->factory();
        $factory
            ->expects($this->once())
            ->method('filter')
            ->with('trim');

        Builder::setDefaultFactory($factory);
        Builder::trim();
    }

    public function testShouldReturnANewInstanceWhenCallingANonExistingMethodStatically()
    {
        $factory = $this->factory();
        $factory
            ->expects($this->any())
            ->method('filter');

        Builder::setDefaultFactory($factory);

        $this->assertInstanceOf('PHPFluent\Filter\Builder', Builder::trim());
    }

    public function testShouldAlwaysReturnANewInstanceWhenCallingANonExistingMethodStatically()
    {
        $factory = $this->factory();
        $factory
            ->expects($this->any())
            ->method('filter');

        Builder::setDefaultFactory($factory);

        $this->assertNotSame(Builder::trim(), Builder::trim());
    }

    public function testShouldReturnAnInstanceWithStackWhenCallingANonExistingMethodStatically()
    {
        $trim = new Callback('trim');

        $factory = $this->factory();
        $factory
            ->expects($this->any())
            ->method('filter')
            ->will($this->returnValue($trim));

        Builder::setDefaultFactory($factory);

        $this->assertEquals(array($trim), Builder::trim()->getFilters());
    }

    public function testShouldCallAllFiltersOnStack()
    {
        $trim = new Callback('trim');
        $stringToUpper = new StringToUpper();
        $factory = $this->factory();
        $factory
            ->expects($this->any())
            ->method('filter')
            ->will($this->onConsecutiveCalls($trim, $stringToUpper));

        $actualString = 'phpfluent filter    ';
        $expectedString = 'PHPFLUENT FILTER';

        $builder = new Builder($factory);
        $builder
            ->trim()
            ->stringToUpper();

        $this->assertEquals($expectedString, $builder->filter($actualString));
    }

    public function testShouldCallAllFiltersOnStackWhenInvokingObject()
    {
        $trim = new Callback('trim');
        $stringToUpper = new StringToUpper();
        $factory = $this->factory();
        $factory
            ->expects($this->any())
            ->method('filter')
            ->will($this->onConsecutiveCalls($trim, $stringToUpper));

        $actualString = 'phpfluent filter    ';
        $expectedString = 'PHPFLUENT FILTER';

        $builder = new Builder($factory);
        $builder
            ->trim()
            ->stringToUpper();

        $this->assertEquals($expectedString, $builder($actualString));
    }
}
