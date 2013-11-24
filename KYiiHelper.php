<?php

/**
 * Helper class to provide easier access to functionality which is
 * already included in Yii but sometimes a is bit difficult to remember.
 *
 * @version 1.0.0 (2013-09-19)
 * @author Denis Komlev <deniskomlev@hotmail.com>
 */
class KYiiHelper
{
    // ------------------------------------------------------------------------

    /**
     * Validate the email address.
     */
    static public function isValidEmail($email)
    {
        $validator = new CEmailValidator;
        return $validator->validateValue($email);
    }
}