<?php

namespace Smt\OpenPopo\Tester\Impl;

use Smt\OpenPopo\Reflection\PopoClass;
use Smt\OpenPopo\Tester\Tester;

/**
 * Base class for property testing
 * @package Smt\OpenPopo\Tester\Impl
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 */
abstract class AbstractPropertyTester implements Tester
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
        $reflection = $class->getReflection();
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED);
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if (in_array($propertyName, $this->skip)) {
                continue;
            }
            $property->setAccessible(true);
            $this->validate($object, $property, $class);
        }
    }

    /**
     * Skip this rule on specified property
     * @param string $propertyName Property to skip
     * @return AbstractPropertyTester This method
     * @api
     */
    public function skip($propertyName)
    {
        $this->skip[] = $propertyName;
        return $this;
    }

    /**
     * Just create new instance of self
     * @return AbstractPropertyTester
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param $object
     * @param \ReflectionProperty $property
     * @param PopoClass $class
     */
    abstract protected function validate($object, \ReflectionProperty $property, PopoClass $class);
}
