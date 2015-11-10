<?php

namespace Smt\OpenPopo\Rule\Impl;

use PHPUnit_Framework_Assert as Assert;
use Smt\OpenPopo\Reflection\PopoClass;
use Smt\OpenPopo\Rule\AbstractPropertyRule;

/**
 * Checks POPO to have getters on all fields
 * @package Smt\OpenPopo\Rule\Impl
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 * @api
 */
class GetterMustExist extends AbstractPropertyRule
{
    /** {@inheritdoc} */
    protected function validate($property, PopoClass $class)
    {
        Assert::assertTrue($class->hasGetter($property), sprintf(
            'Class %s has no getter method for property named "%s"',
            $class,
            $property
        ));
        $method = $class->getGetter($property);
        Assert::assertTrue($method->getNumberOfRequiredParameters() == 0, sprintf(
            'Getter for "%s::%s" has required arguments!',
            $class,
            $property
        ));
    }
}
