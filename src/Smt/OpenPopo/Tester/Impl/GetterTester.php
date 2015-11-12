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
        if (!$class->hasGetter($property)) {
            return;
        }
        Assert::assertEquals($property->getValue($object), $class->getGetter($property)->invoke($object));
        $property->setValue($object, TestDataGenerator::forPrimitive());
        Assert::assertEquals($property->getValue($object), $class->getGetter($property)->invoke($object));
    }
}
