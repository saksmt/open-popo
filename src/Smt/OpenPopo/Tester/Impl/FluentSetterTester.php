<?php

namespace Smt\OpenPopo\Tester\Impl;

use PHPUnit_Framework_Assert as Assert;
use Smt\OpenPopo\Generator\TestDataGenerator;
use Smt\OpenPopo\Reflection\PopoClass;

/**
 * Test fluent setter
 * @package Smt\OpenPopo\Tester\Impl
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 * @api
 */
class FluentSetterTester extends AbstractPropertyTester
{
    /** {@inheritdoc} */
    protected function validate($object, \ReflectionProperty $property, PopoClass $class)
    {
        if (!$class->hasSetter($property)) {
            return;
        }
        $setter = $class->getSetter($property);
        $testData = TestDataGenerator::forParameter($setter->getParameters()[0]);
        Assert::assertEquals($object, $setter->invoke($object, $testData));
        Assert::assertEquals($testData, $property->getValue($object));
    }
}
