<?php

/**
 * Class for working with numeric data.
 *
 * @version 1.4.0 (2014-05-09)
 * @author Denis Komlev <deniskomlev@hotmail.com>
 */
class KNumHelper
{
    // ------------------------------------------------------------------------

    /**
     * Checks if the input value can be correctly interpreted as integer.
     *
     * Examples:
     * isInteger('007', false)         // true
     * isInteger('-007', false)        // true
     * isInteger('+007', false)        // true
     * isInteger('+7')                 // false (should be '7')
     * isInteger('-007')               // false (should be '-7')
     *
     * @param mixed $value
     * @param bool $strict
     * @return bool
     */
    public static function isInteger($value, $strict = true)
    {
        if (!is_numeric($value) && !is_string($value)) {
            return false;
        }

        $str = (string) $value;  // String presentation
        $num = $str + 0;         // Numeric presentation

        // Input value should contain only digits and leading plus or minus sign
        if (preg_match('/^[-+]?[0-9]+$/', $str)) {

            // Check the strict number record (good: 7, -1; bad: +7, -001)
            if ($strict && (strcmp($str, (string) $num) !== 0)) {
                return false;
            }

            return true;
        }

        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns value as integer if it passes isInteger() function or as specified
     * in $default parameter.
     *
     * @param mixed $value Input value
     * @param mixed $default Value to return if input value is not an integer
     * @return int|float|mixed
     */
    public static function toInteger($value, $default = 0)
    {
        return (self::isInteger($value, false)) ? $value + 0 : $default;
    }

    // ------------------------------------------------------------------------

    /**
     * Adjust the numeric value to meet the specified range.
     *
     * If the value is not numeric:
     * - $min will be returned if it is specified and it is numeric;
     * - $max will be returned if it is specified and it is numeric and $min is not specified;
     * - $default will be returned in other case.
     *
     * @param int|float $value Input value
     * @param int|float $min Minimum allowed value
     * @param int|float $max Maximum allowed value
     * @param mixed     $default The default value
     * @return int|float|null
     */
    public static function range($value, $min = null, $max = null, $default = 0)
    {
        if (is_numeric($value)) {
            $value = $value + 0;
            if (is_numeric($min) && $value < $min) {
                $value = $min;
            }
            if (is_numeric($max) && $value > $max) {
                $value = $max;
            }
        }
        else {
            $value = $default;
        }
        return $value;
    }
}