<?php
declare(strict_types=1);

namespace Lucek\FormHandlerBundle\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FormHandlerTestCase extends TestCase
{
    protected function createIteratorMock(string $className): MockObject
    {
        return $this
            ->getMockBuilder($className)
            ->setMethods(['rewind', 'valid', 'current', 'key', 'next'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }

    protected function mockIteratorItems(MockObject $iterator, array $items = [], $includeCallsToKey = false)
    {
        $iterator->expects($this->at(0))->method('rewind');
        $counter = 1;
        foreach ($items as $k => $v) {
            $iterator->expects($this->at($counter++))->method('valid')->will($this->returnValue(true));
            $iterator->expects($this->at($counter++))->method('current')->will($this->returnValue($v));
            if ($includeCallsToKey) {
                $iterator->expects($this->at($counter++))->method('key')->will($this->returnValue($k));
            }
            $iterator->expects($this->at($counter++))->method('next');
        }
        $iterator->expects($this->at($counter))->method('valid')->will($this->returnValue(false));
    }
}