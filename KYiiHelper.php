<?php

/**
 * Helper class to provide easier access to functionality which is
 * already included in Yii but sometimes a is bit difficult to remember.
 *
 * @version 1.0.1 (2013-12-24)
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

    // ------------------------------------------------------------------------

    static public function getCookie($name)
    {
        return Yii::app()->request->cookies->contains($name)
            ? Yii::app()->request->cookies[$name]->value
            : null;
    }

    // ------------------------------------------------------------------------

    static public function setCookie($name, $value, $options = array())
    {
        if (isset($options['time'])) {
            $options['expire'] = time() + $options['time'];
            unset($options['time']);
        }
        Yii::app()->request->cookies[$name] = new CHttpCookie($name, $value, $options);
    }

    // ------------------------------------------------------------------------

    static public function removeCookie($name, $options = array())
    {
        Yii::app()->request->cookies->remove($name, $options);
    }
}