open-popo
=========

Small library for quick testing your Plain Old PHP Objects.

*(inspired by open-pojo)*

Installation
============

    composer require smt/open-popo

Usage
=====

Example
-------


    <?php

    use Smt\OpenPopo\Reflection\PopoClass;
    use Smt\OpenPopo\Validator\Validator;
    use Smt\OpenPopo\Rule\Impl\GetterMustExist;
    use Smt\OpenPopo\Rule\Impl\SetterMustExist;
    use Smt\OpenPopo\Tester\Impl\GetterTester;
    use Smt\OpenPopo\Tester\Impl\FluentSetterTester; // You can also remove "Fluent" prefix if you don't want to check
                                                     // this functional

    class EntityTest extends PHPUnit_Framework_TestCase
    {
        public function testAll()
        {
            Validator::create()

                ->addRule(GetterMustExist::create())
                ->addRule(
                    SetterMustExist::create()
                        ->skip('iWantThisPropertyToHaveNoSetter')
                )

                ->addTester(GetterTester::create())
                ->addTester(FluentSetterTester::create())

                ->validate(PopoClass::fromClassName(Entity::class)
            ;
        }
    }


Notes (need to rewrite into documentation)
------------------------------------------

Available rules:

 - `GetterMustExist`
 - `SetterMustExist`

Available testers:

 - `GetSetTester` **deprecated**
 - `FluentGetSetTester`**deprecated**
 - `GetterTester`
 - `SetterTester`
 - `FluentSetterTester`

Planned:

 - Collection rules/testers (`add*`/`remove*`)

Every rule and tester:

 - has `skip` method to specify properties to skip
 - can be instantiated directly (via `new`) or via factory method (`::create()`)

Validator can be instantiated directly (via `new`) or via factory method (`::create()`)

License
=======

This package is licensed under [MIT license](https://github.com/saksmt/open-popo/blob/develop/LICENSE)