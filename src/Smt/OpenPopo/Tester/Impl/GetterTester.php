<?php

namespace Smt\OpenPopo\Tester\Impl;

use PHPUnit_Framework_Assert as Assert;
use Smt\OpenPopo\Generator\TestDataGenerator;
use Smt\OpenPopo\Reflection\PopoClass;

/**
 * Test getter
 * @package Smt\OpenPopo\Tester\Impl
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 * @api
 */
class GetterTester extends AbstractPropertyTester
{
    /** {@inheritdoc} */
    protected function validate($object, \ReflectionProperty $property, PopoClass $class)
    {
        $propertyName = $property->getName();
        if (!$class->hasGetter($propertyName)) {
            return;
        }
        $getter = $class->getGetter($propertyName);
        Assert::assertEquals($property->getValue($object), $getter->invoke($object), sprintf(
            'Failed on %s::%s()',
            get_class($object),
            $getter->getName()
        ));
        $property->setValue($object, TestDataGenerator::forPrimitive());
        Assert::assertEquals($property->getValue($object), $getter->invoke($object), sprintf(
            'Failed on %s::%s()',
            get_class($object),
            $getter->getName()
        ));
    }
}
