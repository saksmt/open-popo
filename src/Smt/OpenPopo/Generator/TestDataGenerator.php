<?php

namespace Smt\OpenPopo\Generator;

use Smt\OpenPopo\Generator\Exception\DataGenerationException;

/**
 * Generates test data
 * @package Smt\OpenPopo\Generator
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 */
class TestDataGenerator
{
    /**
     * @param \ReflectionParameter $parameter Parameter for which data should be generated
     * @return mixed Generated data
     * @throws DataGenerationException
     */
    public static function forParameter(\ReflectionParameter $parameter)
    {
        if ($parameter->getClass() === null) {
            switch (true) {
                case $parameter->isArray():
                    return self::forArray();
                case $parameter->isCallable():
                    return self::forCallable();
                default:
                    return self::forPrimitive();
            }
        }
        $dataClass = $parameter->getClass();
        try {
            return self::forClass($parameter->getClass());
        } catch (DataGenerationException $e) {
            throw new DataGenerationException(sprintf(
                'Can`t generate test data for %s::%s(/* ... */ %s $%s /* ... */)',
                $parameter->getDeclaringClass()->getName(),
                $parameter->getDeclaringFunction()->getName(),
                $dataClass->getName(),
                $parameter->getName()
            ));
        }
    }

    /**
     * Generate test data for primitive type
     * @return string
     */
    public static function forPrimitive()
    {
        return uniqid();
    }

    /**
     * Generate test data for array type
     * @return array
     */
    public static function forArray()
    {
        return [];
    }

    /**
     * Generate test data for callable type
     * @return \Closure
     */
    public static function forCallable()
    {
        return function () {
        };
    }

    /**
     * Generate test data of specified type
     * @param \ReflectionClass $class Type
     * @return object
     * @throws DataGenerationException
     */
    public static function forClass(\ReflectionClass $class)
    {
        if (!$class->isAbstract() && !$class->isTrait() && !$class->isInterface()) {
            if ($class->getConstructor() !== null) {
                $constructor = $class->getConstructor();
                $constructor->setAccessible(true);
                $instance = $class->newInstanceWithoutConstructor();
                $constructor->invokeArgs($instance, self::forParameterList($class->getConstructor()->getParameters()));
                return $instance;
            }
            return $class->newInstance();
        }
        throw new DataGenerationException(sprintf(
            'Can`t generate test data of class %s',
            $class->getName()
        ));
    }

    /**
     * @param \ReflectionParameter[] $parameters Parameters for which data should be generated
     * @return mixed Generated data
     * @throws DataGenerationException
     */
    public static function forParameterList(array $parameters)
    {
        $arguments = [];
        foreach ($parameters as $argument) {
            if ($argument->isOptional()) {
                $arguments[] = null;
            } else {
                $arguments[] = self::forParameter($argument);
            }
        }
        return $arguments;
    }
}
