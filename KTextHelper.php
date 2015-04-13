<?php

/**
 * Helper class for working with text data.
 *
 * @version 1.3.6 (2014-08-14)
 * @author Denis Komlev <deniskomlev@hotmail.com>
 */
class KTextHelper
{
    // ------------------------------------------------------------------------

    /**
     * Avoid unobvious results when using something like empty('0').
     */
    public static function isEmptyString($string)
    {
        return strcmp($string, '') === 0;
    }

    // ------------------------------------------------------------------------

    /**
     * Remove the substring from beginning of string.
     */
    public static function trimPrefix($string, $prefix)
    {
        return preg_replace('/^'.preg_quote($prefix, '/').'/u', '', $string);
    }

    // ------------------------------------------------------------------------

    /**
     * Remove the substring from end of string.
     */
    public static function trimSuffix($string, $suffix)
    {
        return preg_replace('/'.preg_quote($suffix, '/').'$/u', '', $string);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns TRUE if string is beginning with given substring.
     */
    public static function startsWith($string, $substring, $match_case = true)
    {
        $pattern = '/^'.preg_quote($substring, '/').'/';
        $modifiers = ($match_case) ? 'u' : 'ui';
        return (bool) preg_match($pattern.$modifiers, $string);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns TRUE if string is ending with given substring.
     */
    public static function endsWith($string, $substring, $match_case = true)
    {
        $pattern = '/'.preg_quote($substring, '/').'$/';
        $modifiers = ($match_case) ? 'u' : 'ui';
        return (bool) preg_match($pattern.$modifiers, $string);
    }

    // ------------------------------------------------------------------------

    /**
     * Replaces placeholders in text with array values.
     *
     * @param string $text
     * @param array $data
     * @param string $tag_start
     * @param string $tag_end
     * @return string
     */
    public static function parsePlaceholders($text, $data, $tag_start = '{', $tag_end = '}')
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $tag = $tag_start . $key . $tag_end;
                $text = str_replace($tag, $value, $text);
            }
        }
        return $text;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the article excerpt before cut tag.
     *
     * @param string $text The source text
     * @param string $more_tag [optional] Cut tag (default "<!--more-->")
     * @param string $encoding [optional] Character encoding (default "UTF-8")
     * @return string
     */
    public static function getExcerpt($text, $more_tag = '<!--more-->', $encoding = 'UTF-8')
    {
        $position = mb_strpos($text, $more_tag, 0, $encoding);

        if ($position === false) {
            $output = $text;
        }
        else {
            $output = trim(mb_substr($text, 0, $position, $encoding));
        }

        return $output;
    }

    // ------------------------------------------------------------------------

    /**
     * Removes first occurrence of "more" tag.
     */
    public static function stripMoreTag($text, $more_tag = '<!--more-->', $encoding = 'UTF-8')
    {
        $position = mb_strpos($text, $more_tag, 0, $encoding);

        if ($position === false) {
            $output = $text;
        }
        else {
            $part1 = mb_substr($text, 0, $position, $encoding);
            $part2 = mb_substr($text, $position + mb_strlen($more_tag, $encoding), mb_strlen($text, $encoding), $encoding);
            $output =  $part1 . $part2;
        }

        return $output;
    }

    // ------------------------------------------------------------------------

    /**
     * Ellipsize string (adapted from CodeIgniter Text helper)
     *
     * This function will strip tags from a string, split it at a defined maximum
     * length, and insert an ellipsis.
     *
     * The first parameter is the string to ellipsize, the second is the number
     * of characters in the final string. The third parameter is where in the string
     * the ellipsis should appear from 0 - 1, left to right. For example. a value
     * of 1 will place the ellipsis at the right of the string, .5 in the middle,
     * and 0 at the left.
     *
     * @param string $string the string to ellipsize
     * @param integer $maxLength max length of the string
     * @param mixed $position int (1|0) or float, .5, .2, etc for position to split
     * @param string $ellipsis the end character (ellipsis by default)
     * @return string
     */
    public static function ellipsize($string, $maxLength, $position = 1, $ellipsis = '&#8230;')
    {
        $string = trim(strip_tags($string));

        if (mb_strlen($string, 'UTF-8') <= $maxLength)
            return $string;

        $begin = mb_substr($string, 0, floor($maxLength * $position), 'UTF-8');
        $position = ($position > 1) ? 1 : $position;

        if ($position === 1)
            $end = mb_substr($string, 0, -($maxLength - mb_strlen($begin, 'UTF-8')), 'UTF-8');
        else
            $end = mb_substr($string, -($maxLength - mb_strlen($begin, 'UTF-8')), 'UTF-8');

        return $begin.$ellipsis.$end;
    }

    // ------------------------------------------------------------------------

    /**
     * Limits the string based on the character count. Tries to preserve complete words
     * so the character count may not be exactly as specified.
     *
     * @param  string  $input
     * @param  integer $maxLength
     * @param  string  $ellipsis the end character (ellipsis by default)
     * @return string
     */
    public static function limitChars($input, $maxLength = 500, $ellipsis = '&#8230;')
    {
        $input = str_replace(array("\r\n", "\r", "\n"), ' ', $input);
        $input = preg_replace('/\s+/', ' ', $input);
        $input = trim($input);

        if (self::length($input) <= $maxLength) {
            return $input;
        }

        $output = '';
        $words = explode(' ', $input);

        foreach ($words as $index => $word) {
            $tmp = trim($output . ' ' . $word);
            if (self::length($tmp) > $maxLength) {
                if ($index === 0 || ($maxLength - self::length($output) > 10)) {
                    // By default, the last word should be omitted. But if
                    // the result become too short (the last word was too long),
                    // then do the "hard" limit regardless the words.
                    $output = mb_substr($tmp, 0, $maxLength - 1);
                }
                // Try to avoid punctuation marks at the string end.
                $output = trim(rtrim($output, '.,;:…'));
                return $output . $ellipsis;
            } else {
                $output = $tmp;
            }
        }

        return $output;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns array of unique chars used in a string and count of every char instance.
     *
     * @param  string $string the input string
     * @return array the array where key is character and value is count
     */
    public static function countUniqueChars($string)
    {
        $uniqueChars = array();
        if (is_string($string)) {
            $length = mb_strlen($string, 'UTF-8');
            for ($i = 0; $i < $length; $i++) {
                $char = mb_substr($string, $i, 1, 'UTF-8');
                if (!array_key_exists($char, $uniqueChars)) {
                    $uniqueChars[$char] = 0;
                }
                $uniqueChars[$char]++;
            }
        }
        return $uniqueChars;
    }

    // ------------------------------------------------------------------------

    /**
     * Randomizes characters in string.
     *
     * @param  string $input the input string
     * @return string
     */
    public static function shuffleString($input)
    {
        $result = $input;
        $uniqueChars = self::countUniqueChars($input);

        if (count($uniqueChars) > 1) {
            do {
                $array = array();
                for ($i = 0; $i < self::length($input); $i++) {
                    $array[] = self::char($i, $input);
                }
                shuffle($array);
                $result = implode('', $array);
            } while ($input === $result);
        }

        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Splits string and returns array of characters.
     *
     * @param  string $string
     * @return string
     */
    public static function stringToArray($string)
    {
        $result = array();
        for ($i = 0; $i < self::length($string); $i++) {
            $result[] = self::char($i, $string);
        }
        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Joins array values and returns it as string.
     *
     * @param  array  $array
     * @return string
     */
    public static function arrayToString($array)
    {
        return implode('', $array);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the word length.
     *
     * @param  string $input the input string
     * @return int
     */
    public static function length($input)
    {
        return mb_strlen($input, 'UTF-8');
    }

    // ------------------------------------------------------------------------

    /**
     * Extracts the single word letter by index.
     *
     * @param  int    $i     character index starting from 0
     * @param  string $input the input string
     * @return string
     */
    public static function char($i, $input)
    {
        return mb_substr($input, $i, 1, 'UTF-8');
    }
}