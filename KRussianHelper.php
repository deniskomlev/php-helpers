<?php

class KRussianHelper
{

    public static $monthNames = array(
        1  => 'Январь',
        2  => 'Февраль',
        3  => 'Март',
        4  => 'Апрель',
        5  => 'Май',
        6  => 'Июнь',
        7  => 'Июль',
        8  => 'Август',
        9  => 'Сентябрь',
        10 => 'Октябрь',
        11 => 'Ноябрь',
        12 => 'Декабрь',
    );

    public static $monthNamesShort = array(
        1  => 'Янв',
        2  => 'Фев',
        3  => 'Мар',
        4  => 'Апр',
        5  => 'Май',
        6  => 'Июн',
        7  => 'Июл',
        8  => 'Авг',
        9  => 'Сен',
        10 => 'Окт',
        11 => 'Ноя',
        12 => 'Дек',
    );

    public static $monthNamesGenitive = array(
        1  => 'января',
        2  => 'февраля',
        3  => 'марта',
        4  => 'апреля',
        5  => 'мая',
        6  => 'июня',
        7  => 'июля',
        8  => 'августа',
        9  => 'сентября',
        10 => 'октября',
        11 => 'ноября',
        12 => 'декабря',
    );

    public static $dayNames = array(
        0 => 'Воскресенье',
        1 => 'Понедельник',
        2 => 'Вторник',
        3 => 'Среда',
        4 => 'Четверг',
        5 => 'Пятница',
        6 => 'Суббота',
    );

    public static $dayNamesShort = array(
        0 => 'Вс',
        1 => 'Пн',
        2 => 'Вт',
        3 => 'Ср',
        4 => 'Чт',
        5 => 'Пт',
        6 => 'Сб',
    );

    // ------------------------------------------------------------------------

    public static function getMonthName($month)
    {
        return self::$monthNames[$month];
    }

    // ------------------------------------------------------------------------

    public static function getMonthNameShort($month)
    {
        return self::$monthNamesShort[$month];
    }

    // ------------------------------------------------------------------------

    public static function getMonthNameGenitive($month)
    {
        return self::$monthNamesGenitive[$month];
    }

    // ------------------------------------------------------------------------

    public static function getDayName($day)
    {
        return self::$dayNames[$day];
    }

    // ------------------------------------------------------------------------

    public static function getDayNameShort($day)
    {
        return self::$dayNamesShort[$day];
    }

}