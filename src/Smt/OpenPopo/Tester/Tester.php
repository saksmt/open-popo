<?php

namespace Smt\OpenPopo\Tester;

use Smt\OpenPopo\Reflection\PopoClass;

/**
 * Represents tester which tests POPO
 * @package Smt\OpenPopo\Tester
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 * @api
 */
interface Tester
{
    /**
     * Test object
     * @param PopoClass $class Class representation of object to test
     * @param object $object Instance to test
     */
    public function test(PopoClass $class, $object);
}
