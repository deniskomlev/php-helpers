<?php

/**
 * Helper class for working with date and time.
 *
 * @version 1.2 (2013-05-29)
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

    public static function getDaysInYear($year)
    {
        return date('z', mktime(0, 0, 0, 12, 31, $year));
    }

    // ------------------------------------------------------------------------

    public static function getMaxDayOfMonth($month)
    {
        $daysInMonth = array(null,31,29,31,30,31,30,31,31,30,31,30,31);
        return (isset($daysInMonth[$month])) ? $daysInMonth[$month] : null;
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
}