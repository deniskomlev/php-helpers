<?php

/**
 * Helper class for working with date and time.
 *
 * @version 1.5.0 (2015-06-02)
 * @author Denis Komlev <deniskomlev@hotmail.com>
 */
class KDateTimeHelper
{

    // ------------------------------------------------------------------------

    /**
     * Returns count of days in given year.
     *
     * @param int $year
     * @return int
     */
    public static function getNumberOfDaysInYear($year)
    {
        $year = (int) $year;
        return self::isLeapYear($year) ? 366 : 365;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns last day of given month. If year is specified, also checks
     * for leap year.
     *
     * @param int $month
     * @param int|bool $year
     * @return int
     * @throws Exception
     */
    public static function getLastMonthDay($month, $year = false)
    {
        $month = (int) $month;

        if ($month < 1 || $month > 12) {
            throw new Exception('Month must be an integer between 1 and 12.');
        }

        $daysInMonth = array(null,31,29,31,30,31,30,31,31,30,31,30,31);

        if ($year !== false && $month == 2) {
            return (self::isLeapYear($year)) ? 29 : 28;
        }

        return $daysInMonth[$month];
    }

    // ------------------------------------------------------------------------

    /**
     * Formats the date.
     *
     * @param string $date any date accepted by strtotime function
     * @param string $format any format accepted by date function
     * @return string
     */
    public static function format($date, $format)
    {
        $timestamp = strtotime($date);
        return date($format, $timestamp);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns an age in years passed from given date.
     *
     * @param $date
     * @return int
     */
    public static function age($date)
    {
        $age = 0;
        $timestamp = strtotime($date);

        if ($timestamp)
        {
            $year = date('Y', $timestamp);
            $month = date('n', $timestamp);
            $day = date('j', $timestamp);
            $age = date('Y') - $year;
            if (intval($month.str_pad($day, 2, '0', STR_PAD_LEFT)) > date('nd')) {
                $age--;
            }
        }

        return ($age > 0) ? $age : 0;
    }

    // ------------------------------------------------------------------------

    /**
     * (c) Yii Framework
     *
     * Checks for leap year, returns true if it is. No 2-digit year check. Also
     * handles julian calendar correctly.
     *
     * @param integer $year year to check
     * @return boolean true if is leap year
     */
    public static function isLeapYear($year)
    {
        $year = (int) $year;

        if ($year % 4 != 0) {
            return false;
        }

        if ($year % 400 == 0) {
            return true;
        }
        // if gregorian calendar (>1582), century not-divisible by 400 is not leap
        elseif ($year > 1582 && $year % 100 == 0 ) {
            return false;
        }

        return true;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns difference in months between two dates (YYYY-MM-DD format).
     * Day of month is not considered.
     *
     * @param string $startDate
     * @param string $endDate
     * @return int|false
     */
    public static function monthsBetween($startDate, $endDate)
    {
        $result = false;

        $splitStart = explode('-', $startDate);
        $splitEnd = explode('-', $endDate);

        if (is_array($splitStart) && is_array($splitEnd)) {
            $difYears = $splitEnd[0] - $splitStart[0];
            $difMonths = $splitEnd[1] - $splitStart[1];

            $result = $difMonths + ($difYears * 12);
        }

        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Change the given date by adding or subtracting specified amount of months.
     * Only month and year are modified; day of month doesn't considered.
     *
     * @param string $date in format "YYYY-MM-DD"
     * @param int $numMonths how many months to add (negative values to subtract)
     * @return string|false
     * @throws Exception
     */
    public static function increaseMonth($date, $numMonths)
    {
        $result = false;

        $split = explode('-', $date);

        if (is_array($split)) {
            $year = $split[0];
            $month = $split[1] + (int) $numMonths;
            $day = $split[2];

            if ($month > 12) {
                $yearFix = (int)floor($month / 12);
                $year = $year + $yearFix;
                $month = $month - (12 * $yearFix);
            } elseif ($month < 1) {
                $yearFix = (int)ceil((abs($month) + 1) / 12);
                $year = $year - $yearFix;
                $month = (12 * $yearFix) - abs($month);
            }

            $lastMonthDay = self::getLastMonthDay($month, $year);

            if ($day > $lastMonthDay) {
                $day = $lastMonthDay;
            }

            $result = sprintf("%04d-%02d-%02d", $year, $month, $day);
        }

        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns DateTime object in UTC timezone.
     *
     * @param string $time
     * @return DateTime
     */
    public static function getUtcDateTime($time = 'now')
    {
        $dateTime = new DateTime($time, new DateTimeZone('UTC'));
        return $dateTime;
    }

    // ------------------------------------------------------------------------

    /**
     * Checks whether the give timezone identifier is valid.
     *
     * @param string $timeZone
     * @return bool
     */
    public static function isValidTimeZone($timeZone)
    {
        return in_array($timeZone, DateTimeZone::listIdentifiers());
    }

}