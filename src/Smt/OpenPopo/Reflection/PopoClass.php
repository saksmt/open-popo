<?php

namespace Smt\OpenPopo\Reflection;

use Smt\OpenPopo\Generator\TestDataGenerator;
use Smt\OpenPopo\Reflection\Exception\NoGetterException;
use Smt\OpenPopo\Reflection\Exception\NoSetterException;

/**
 * Simplification over standard reflection class
 * @package Smt\OpenPopo\Reflection
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 * @api
 */
class PopoClass
{
    /**
     * @var \ReflectionClass
     */
    private $reflection;

    /**
     * @var string
     */
    private $name;

    /**
     * Constructor.
     * @param string $className
     */
    private function __construct($className)
    {
        $this->name = $className;
        $this->reflection = new \ReflectionClass($className);
    }

    /**
     * @param string $className Valid fully qualified class name
     * @return PopoClass
     * @api
     */
    public static function fromClassName($className)
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" doesn`t exist!', $className));
        }
        return new self($className);
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflection()
    {
        return $this->reflection;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $propertyName Property name
     * @return bool
     */
    public function hasGetter($propertyName)
    {
        return $this->findGetter($propertyName) !== null;
    }

    /**
     * @param string $propertyName Property name
     * @return bool
     */
    public function hasSetter($propertyName)
    {
        return $this->findSetter($propertyName) !== null;
    }

    /**
     * @param string $propertyName Property name
     * @return \ReflectionMethod
     * @throws NoGetterException
     */
    public function getGetter($propertyName)
    {
        $getterName = $this->findGetter($propertyName);
        if (!isset($getterName)) {
            throw new NoGetterException(sprintf('No getter found for property %s of class %s', $propertyName, $this->name));
        }
        return $this->reflection->getMethod($getterName);
    }

    /**
     * @param string $propertyName Property name
     * @return \ReflectionMethod
     * @throws NoSetterException
     */
    public function getSetter($propertyName)
    {
        $setterName = $this->findSetter($propertyName);
        if (!isset($setterName)) {
            throw new NoSetterException(sprintf('No getter found for property %s of class %s', $propertyName, $this->name));
        }
        return $this->reflection->getMethod($setterName);
    }

    /**
     * Check that both accessors exists for property
     * @param string $property Property name
     * @return bool
     */
    public function hasAccessors($property)
    {
        return ($this->hasGetter($property) && $this->hasSetter($property));
    }

    /**
     * @return object Instance of class
     */
    public function instance()
    {
        if ($this->reflection->getConstructor() !== null) {
            return $this->reflection->newInstanceArgs(
                TestDataGenerator::forParameterList($this->reflection->getConstructor()->getParameters())
            );
        }
        return $this->reflection->newInstance();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param string $propertyName
     * @return null|string
     */
    private function findGetter($propertyName)
    {
        return $this->findPrefixedMethod($propertyName, ['is', 'has', 'get']);
    }

    /**
     * @param string $propertyName
     * @return null|string
     */
    private function findSetter($propertyName)
    {
        return $this->findPrefixedMethod($propertyName, ['set']);
    }

    /**
     * @param string $name
     * @param string[] $prefixes
     * @return null|string
     */
    private function findPrefixedMethod($name, $prefixes)
    {
        $name = ucfirst($name);
        foreach ($prefixes as $prefix) {
            if ($this->reflection->hasMethod($prefix . $name)) {
                return $prefix . $name;
            }
        }
        return null;
    }
}
