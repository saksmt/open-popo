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
                    return [];
                case $parameter->isCallable():
                    return function () {
                    };
                default:
                    return uniqid();
            }
        }
        $dataClass = $parameter->getClass();
        if (!$dataClass->isAbstract() && !$dataClass->isTrait() && !$dataClass->isInterface()) {
            if ($dataClass->getConstructor() !== null) {
                $constructor = $dataClass->getConstructor();
                $constructor->setAccessible(true);
                $instance = $dataClass->newInstanceWithoutConstructor();
                $constructor->invokeArgs($instance, self::forParameterList($dataClass->getConstructor()->getParameters()));
                return $instance;
            }
            return $dataClass->newInstance();
        }
        throw new DataGenerationException(sprintf(
            'Can`t generate test data for %s::%s(/* ... */ %s $%s /* ... */)',
            $parameter->getDeclaringClass()->getName(),
            $parameter->getDeclaringFunction()->getName(),
            $dataClass->getName(),
            $parameter->getName()
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
