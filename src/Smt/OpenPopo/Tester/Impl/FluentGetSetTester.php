<?php

namespace Smt\OpenPopo\Tester\Impl;

use PHPUnit_Framework_Assert as Assert;
use Smt\OpenPopo\Generator\TestDataGenerator;

/**
 * Test get/set methods expecting set to be fluent
 * @package Smt\OpenPopo\Tester\Impl
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 * @api
 * @deprecated
 */
class FluentGetSetTester extends GetSetTester
{
    /** {@inheritdoc} */
    protected function validate($object, $defaultValue, \ReflectionMethod $getterMethod, \ReflectionMethod $setterMethod)
    {
        $testData = TestDataGenerator::forParameter($setterMethod->getParameters()[0]);
        Assert::assertEquals($defaultValue, $getterMethod->invoke($object), sprintf(
            'Failed on %s::%s()',
            get_class($object),
            $getterMethod->getName()
        ));
        Assert::assertEquals($object, $setterMethod->invoke($object, $testData), sprintf(
            '%s::%s() is not fluent', get_class($object),
            $setterMethod->getName()
        ));
        Assert::assertEquals($testData, $getterMethod->invoke($object), sprintf(
            'Failed on %s::%s() - can`t get written value',
            get_class($object),
            $getterMethod->getName()
        ));
    }
}
