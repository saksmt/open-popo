<?php

namespace Smt\OpenPopo\Rule;

use Smt\OpenPopo\Reflection\PopoClass;

/**
 * Represents rule that POPO must follow
 * @package Smt\OpenPopo\Rule
 * @author Kirill Saksin <kirillsaksin@yandex.ru>
 * @api
 */
interface Rule
{
    /**
     * Check if class follow rule
     * @param PopoClass $class Class to check
     */
    public function check(PopoClass $class);
}
