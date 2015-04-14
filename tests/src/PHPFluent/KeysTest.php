<?php

namespace PHPFluent\Filter;

/**
 * @covers PHPFluent\Filter\Keys
 */
class KeysTest extends \PHPUnit_Framework_TestCase
{
    private function getFilterMock()
    {
        return $this->getMock('PHPFluent\\Filter\\FilterInterface');
    }

    private function getFilterMockWithReturn($filtered)
    {
        $filterMock = $this->getFilterMock();
        $filterMock
            ->expects($this->any())
            ->method('filter')
            ->will($this->returnValue($filtered));

        return $filterMock;
    }

    public function testShouldUseFilterOverKey()
    {
        $key = 'foo';
        $value = 'something';

        $filterMock = $this->getFilterMock();
        $filterMock
            ->expects($this->once())
            ->method('filter')
            ->with($value);

        $input = array($key => $value);

        $filter = new Keys(array($key => $filterMock));
        $filter->filter($input);
    }

    public function testShouldNotUseFilterWhenKeyDoeNotExists()
    {
        $key = 'foo';

        $filterMock = $this->getFilterMock();
        $filterMock
            ->expects($this->never())
            ->method('filter');

        $filter = new Keys(array($key => $filterMock));
        $filter->filter(array());
    }

    public function testShouldReturnFilteredValue()
    {
        $keys = array(
            'foo' => $this->getFilterMockWithReturn('foo filtered value'),
            'bar' => $this->getFilterMockWithReturn('bar filtered value'),
        );

        $input = array(
            'foo' => 'foo value',
            'bar' => 'bar value',
            'baz' => 'baz value',
        );

        $expectedResult = array(
            'foo' => 'foo filtered value',
            'bar' => 'bar filtered value',
        );

        $filter = new Keys($keys);

        $this->assertEquals($expectedResult, $filter->filter($input));
    }

    public function testShouldReturnFilteredValueEvenWhenRecursive()
    {
        $keys = array(
            'foo' => new Keys(
                array(
                    'bar' => new Keys(
                        array(
                            'baz' => $this->getFilterMockWithReturn('baz filtered value'),
                        )
                    ),
                )
            ),
        );

        $input = array(
            'foo' => array(
                'bar' => array(
                    'baz' => 'baz value',
                ),
            ),
            'quox' => 'quox value',
        );

        $expectedResult = array(
            'foo' => array(
                'bar' => array(
                    'baz' => 'baz filtered value',
                ),
            ),
        );

        $filter = new Keys($keys);

        $this->assertEquals($expectedResult, $filter->filter($input));
    }
}
