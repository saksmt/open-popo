<?php

namespace Smt\OpenPopo\Validator;

use Smt\OpenPopo\Reflection\PopoClass;
use Smt\OpenPopo\Rule\Rule;
use Smt\OpenPopo\Tester\Tester;

/**
 * Validates object
 * @package Smt\OpenPopo\Validator
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 * @api
 */
class Validator
{
    /**
     * @var Tester[]
     */
    private $testers = [];

    /**
     * @var Rule[]
     */
    private $rules = [];

    /**
     * @param Rule $rule Rule
     * @return Validator This instance
     * @api
     */
    public function addRule(Rule $rule)
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * @param Tester $tester Tester
     * @return Validator This instance
     * @api
     */
    public function addTester(Tester $tester)
    {
        $this->testers[] = $tester;
        return $this;
    }

    /**
     * Validate class
     * @param PopoClass $class Class to validate
     * @api
     */
    public function validate(PopoClass $class)
    {
        foreach ($this->rules as $rule) {
            $rule->check($class);
        }
        $object = $class->instance();
        foreach ($this->testers as $tester) {
            $tester->test($class, $object);
        }
    }

    /**
     * Just creates new instance of self
     * @return Validator
     * @api
     */
    public static function create()
    {
        return new self();
    }
}
