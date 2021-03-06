<?php

namespace Smt\OpenPopo\Tester\Impl;

use PHPUnit_Framework_Assert as Assert;
use Smt\OpenPopo\Generator\TestDataGenerator;
use Smt\OpenPopo\Reflection\PopoClass;

/**
 * Test setter
 * @package Smt\OpenPopo\Tester\Impl
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 * @api
 */
class SetterTester extends AbstractPropertyTester
{
    /** {@inheritdoc} */
    protected function validate($object, \ReflectionProperty $property, PopoClass $class)
    {
        if (!$class->hasSetter($property->getName())) {
            return;
        }
        $setter = $class->getSetter($property->getName());
        $testData = TestDataGenerator::forParameter($setter->getParameters()[0]);
        $setter->invoke($object, $testData);
        Assert::assertEquals($testData, $property->getValue($object), sprintf(
            'Failed on %s::%s() - written value doesn`t appear in property',
            get_class($object),
            $setter->getName()
        ));
    }
}
