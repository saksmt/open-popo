<?php

namespace Smt\OpenPopo\Rule\Impl;

use PHPUnit_Framework_Assert as Assert;
use Smt\OpenPopo\Reflection\PopoClass;
use Smt\OpenPopo\Rule\AbstractPropertyRule;

/**
 * Checks POPO to have setters on all fields
 * @package Smt\OpenPopo\Rule\Impl
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 * @api
 */
class SetterMustExist extends AbstractPropertyRule
{

    /** {@inheritdoc} */
    protected function validate($property, PopoClass $class)
    {
        Assert::assertTrue($class->hasSetter($property), sprintf(
            'Class %s has no setter method for property named "%s"',
            $class,
            $property
        ));
        $method = $class->getSetter($property);
        Assert::assertTrue($method->getNumberOfRequiredParameters() == 1, sprintf(
            'Setter for "%s::%s" has more or less than one required argument!',
            $class,
            $property
        ));
        Assert::assertTrue($method->getNumberOfParameters() == 1, sprintf(
            'Setter for "%s::%s" has more argument!',
            $class,
            $property
        ));
    }
}
