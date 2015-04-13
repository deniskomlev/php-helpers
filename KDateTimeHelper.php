<?php

/**
 * Helper class for working with date and time.
 *
 * @version 1.4 (2015-04-13)
 * @author Denis Komlev <deniskomlev@hotmail.com>
 */
class KDateTimeHelper
{

    private static $_monthNames = array(
        'en'=>array(
            1 =>array('January',   'Jan'),
            2 =>array('February',  'Feb'),
            3 =>array('March',     'Mar'),
            4 =>array('April',     'Apr'),
            5 =>array('May',       'May'),
            6 =>array('June',      'Jun'),
            7 =>array('July',      'Jul'),
            8 =>array('August',    'Aug'),
            9 =>array('September', 'Sep'),
            10=>array('October',   'Oct'),
            11=>array('November',  'Nov'),
            12=>array('December',  'Dec')
        ),
        'ru'=>array(
            1 =>array('Январь',   'Янв'),
            2 =>array('Февраль',  'Фев'),
            3 =>array('Март',     'Мар'),
            4 =>array('Апрель',   'Апр'),
            5 =>array('Май',      'Май'),
            6 =>array('Июнь',     'Июн'),
            7 =>array('Июль',     'Июл'),
            8 =>array('Август',   'Авг'),
            9 =>array('Сентябрь', 'Сен'),
            10=>array('Октябрь',  'Окт'),
            11=>array('Ноябрь',   'Ноя'),
            12=>array('Декабрь',  'Дек')
        )
    );

    private static $_dayNames = array(
        'en'=>array(
            0=>array('Sunday',    'Sun'),
            1=>array('Monday',    'Mon'),
            2=>array('Tuesday',   'Tue'),
            3=>array('Wednesday', 'Wed'),
            4=>array('Thursday',  'Thu'),
            5=>array('Friday',    'Fri'),
            6=>array('Saturday',  'Sat')
        ),
        'ru'=>array(
            0=>array('Воскресенье', 'Вс'),
            1=>array('Понедельник', 'Пн'),
            2=>array('Вторник',     'Вт'),
            3=>array('Среда',       'Ср'),
            4=>array('Четверг',     'Чт'),
            5=>array('Пятница',     'Пт'),
            6=>array('Суббота',     'Сб')
        )
    );

    // ------------------------------------------------------------------------

    public static function getMonthName($month, $lang = 'en')
    {
        if (!isset(self::$_monthNames[$lang]))
            $lang = 'en';

        return (isset(self::$_monthNames[$lang][$month][0]))
            ? self::$_monthNames[$lang][$month][0]
            : false;
    }

    // ------------------------------------------------------------------------

    public static function getMonthNameShort($month, $lang = 'en')
    {
        if (!isset(self::$_monthNames[$lang]))
            $lang = 'en';

        return (isset(self::$_monthNames[$lang][$month][1]))
            ? self::$_monthNames[$lang][$month][1]
            : false;
    }

    // ------------------------------------------------------------------------

    public static function getDayName($day, $lang = 'en')
    {
        if (!isset(self::$_dayNames[$lang]))
            $lang = 'en';

        return (isset(self::$_dayNames[$lang][$day][0]))
            ? self::$_dayNames[$lang][$day][0]
            : false;
    }

    // ------------------------------------------------------------------------

    public static function getDayNameShort($day, $lang = 'en')
    {
        if (!isset(self::$_dayNames[$lang]))
            $lang = 'en';

        return (isset(self::$_dayNames[$lang][$day][1]))
            ? self::$_dayNames[$lang][$day][1]
            : false;
    }

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

}