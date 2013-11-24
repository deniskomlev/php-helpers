<?php

/**
 * Helper class for working with text data.
 *
 * @version 1.3.1 (2013-05-21)
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
     * Removes first occurence of "more" tag.
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
     * @param	string		string to ellipsize
     * @param	integer		max length of string
     * @param	mixed		int (1|0) or float, .5, .2, etc for position to split
     * @param	string		ellipsis ; Default '...'
     * @return	string		ellipsized string
     */
    public static function ellipsize($string, $maxLength, $position = 1, $ellipsis = '&hellip;')
    {
        $string = trim(strip_tags($string));

        // Is the string long enough to ellipsize?
        if (mb_strlen($string, 'UTF-8') <= $maxLength)
            return $string;

        $beg = mb_substr($string, 0, floor($maxLength * $position), 'UTF-8');
        $position = ($position > 1) ? 1 : $position;

        if ($position === 1)
            $end = mb_substr($string, 0, -($maxLength - mb_strlen($beg, 'UTF-8')), 'UTF-8');
        else
            $end = mb_substr($string, -($maxLength - mb_strlen($beg, 'UTF-8')), 'UTF-8');

        return $beg.$ellipsis.$end;
    }
}