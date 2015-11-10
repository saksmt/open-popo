<?php

namespace Smt\OpenPopo\Rule;

use Smt\OpenPopo\Reflection\PopoClass;

/**
 * Base rule for those based on properties
 * @package Smt\OpenPopo\Rule
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 */
abstract class AbstractPropertyRule implements Rule
{
    private $skip = [];

    /** {@inheritdoc} */
    public function check(PopoClass $class)
    {
        $reflection = $class->getReflection();
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
        foreach ($properties as $property) {
            if (!in_array($property->getName(), $this->skip)) {
                $this->validate($property->getName(), $class);
            }
        }
    }

    /**
     * Skip this rule on specified property
     * @param string $propertyName Property to skip
     * @return AbstractPropertyRule
     * @api
     */
    public function skip($propertyName)
    {
        $this->skip[] = $propertyName;
        return $this;
    }

    /**
     * Just create new instance of self
     * @return AbstractPropertyRule
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Perform validation
     * @param string $property
     * @param PopoClass $class
     */
    abstract protected function validate($property, PopoClass $class);
}
