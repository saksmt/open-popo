<?php

namespace Smt\OpenPopo\Tester\Impl;

use PHPUnit_Framework_Assert as Assert;
use Smt\OpenPopo\Generator\TestDataGenerator;
use Smt\OpenPopo\Reflection\PopoClass;
use Smt\OpenPopo\Tester\Tester;

/**
 * Test setter methods of POPO
 * @package Smt\OpenPopo\Tester\Impl
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 * @api
 */
class GetSetTester implements Tester
{
    /**
     * @var string[]
     */
    private $skip = [];

    /**
     * Test object
     * @param PopoClass $class Class representation of object to test
     * @param object $object Instance to test
     */
    public function test(PopoClass $class, $object)
    {
        /** @var \ReflectionClass $reflection */
        $reflection = $class->getReflection();
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED);
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if (!$class->hasAccessors($propertyName) || in_array($propertyName, $this->skip)) {
                continue;
            }
            $getterMethod = $class->getGetter($propertyName);
            $setterMethod = $class->getSetter($propertyName);
            $property->setAccessible(true);
            $defaultValue = $property->getValue($object);
            $this->validate($object, $defaultValue, $getterMethod, $setterMethod);
        }
    }

    /**
     * Skip this rule on specified property
     * @param string $propertyName Property to skip
     * @return GetSetTester This method
     * @api
     */
    public function skip($propertyName)
    {
        $this->skip[] = $propertyName;
        return $this;
    }

    /**
     * Just create new instance of self
     * @return GetSetTester
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param object $object
     * @param mixed $defaultValue
     * @param \ReflectionMethod $getterMethod
     * @param \ReflectionMethod $setterMethod
     */
    protected function validate($object, $defaultValue, \ReflectionMethod $getterMethod, \ReflectionMethod $setterMethod)
    {
        $testData = TestDataGenerator::forParameter($setterMethod->getParameters()[0]);
        Assert::assertEquals($defaultValue, $getterMethod->invoke($object), sprintf(
            'Failed on %s::%s()',
            get_class($object),
            $getterMethod->getName()
        ));
        $setterMethod->invoke($object, $testData);
        Assert::assertEquals($testData, $getterMethod->invoke($object), sprintf(
            'Failed on %s::%s() - can`t get written value',
            get_class($object),
            $getterMethod->getName()
        ));
    }
}
