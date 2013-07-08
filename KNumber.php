<?php

/**
 * Class for working with numeric data.
 *
 * @version 1.3.0 (2013-06-04)
 * @author Denis Komlev <deniskomlev@hotmail.com>
 */
class KNumber
{
    // ------------------------------------------------------------------------

    /**
     * Checks if the input value can be correctly interpreted as integer
     *
     * Examples:
     * check_integer('007')         // true
     * check_integer('-007')        // true
     * check_integer('+007')        // true
     * check_integer('+7', true)    // false (should be '7')
     * check_integer('-007', true)  // false (should be '-7')
     *
     * @param mixed $value Checked value
     * @param bool $strict Set TRUE for use strict mode
     * @param null|int $min Minimum value
     * @param null|int $max Maxinum value
     * @return bool
     */
    public static function checkInteger($value, $strict = false, $min = null, $max = null)
    {
        $str = (string) $value;  // String presentation
        $num = $str + 0;         // Numeric presentation

        // Primary check for the value contain only digits,
        // leading plus or minus sign and leading or trailing spaces
        if (preg_match('/^\s*[-+]?[0-9]+\s*$/', $str)) {

            // Check the strict number record (good: 7, -1; bad: +7, -001)
            if ($strict && (strcmp($str, (string) $num) !== 0)) {
                return false;
            }

            // Check for the maximum and minimum ranges
            if (($min !== null && $num < $min) || ($max !== null && $num > $max)) {
                return false;
            }

            return true;
        }

        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns value as integer or float if it passes checkInteger() function
     *
     * @param mixed $value Input value
     * @param mixed $default Value to return if input value is not an integer
     * @param boolean $strict See check_integer()
     * @return int|float|mixed
     */
    public static function makeInteger($value, $default = 0, $strict)
    {
        return (self::checkInteger($value, $strict)) ? $value + 0 : $default;
    }

    // ------------------------------------------------------------------------

    /**
     * Adjust the numeric value to meet the numeric range.
     *
     * If the value is not numeric:
     * - $min will be returned if it is specified and it is numeric;
     * - $max will be returned if it is specified and it is numeric and $min is not specified;
     * - null will be returned in other case.
     *
     * @param int|float $value Input value
     * @param int|float $min Minimum allowed value
     * @param int|float $max Maximum allowed value
     * @return int|float|null
     */
    public static function limit($value, $min = null, $max = null)
    {
        if (is_numeric($value)) {
            if (is_numeric($min) && $value < $min) {
                $value = $min;
            }
            if (is_numeric($max) && $value > $max) {
                $value = $max;
            }
        }
        else {
            $value = (is_numeric($min) ? $min : (is_numeric($max) ? $max : null));
        }

        return $value;
    }
}