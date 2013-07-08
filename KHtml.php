<?php

/**
 * HTML helper class.
 *
 * @version 1.2.1 (2013-06-06)
 * @author Denis Komlev <deniskomlev@hotmail.com>
 */
class KHtml
{
    // ------------------------------------------------------------------------

    /**
     * Converts associative array to a string of attributes.
     *
     * Input: array('id'=>'myid', 'class'=>'someclass')
     * Output: id="myid" class="someclass"
     */
    public static function attributes($vars)
    {
        $output = '';

        if (is_array($vars) && !empty($vars)) {
            foreach ($vars as $key => $value) {
                $str = $key;
                if ($value !== null) { $str .= '="'.$value.'"'; }
                $params[] = $str;
            }
            $output = implode(' ', $params);
        }

        return $output;
    }

    // ------------------------------------------------------------------------

    /**
     * Alias for htmlspecialchars function.
     */
    public static function chars($str)
    {
        return htmlspecialchars($str);
    }

    // ------------------------------------------------------------------------

    /**
     * Renders <img> tag.
     *
     * @param $src
     * @param array $attr
     * @return string
     */
    public static function img($src, $attr = array())
    {
        $output = '';

        if (!empty($src)) {
            $output .= '<img src="'.$src.'"';
            if (!isset($attr['alt'])) { $attr['alt'] = ''; }
            $output .= self::attributes($attr, ' ');
            $output .= '>';
        }

        return $output;
    }

    // ------------------------------------------------------------------------

    public static function checked($condition)
    {
        if ($condition) return 'checked';
        else return '';
    }

    // ------------------------------------------------------------------------

    public static function selected($condition)
    {
        if ($condition) return 'selected';
        else return '';
    }

    // ------------------------------------------------------------------------

    /**
     * Writes an obfuscated version of the mailto tag using ordinal numbers
     * written with JavaScript to help prevent the email address from being
     * harvested by spam bots.
     */
    static public function safeMailto($email, $title = '', $attributes = '')
    {
        if (($title = (string) $title) == '')
            $title = $email;

        for ($i = 0; $i < 16; $i++)
            $x[] = substr('<a href="mailto:', $i, 1);

        for ($i = 0; $i < strlen($email); $i++)
            $x[] = "|".ord(substr($email, $i, 1));

        $x[] = '"';

        if ($attributes != '') {
            if (is_array($attributes)) {
                foreach ($attributes as $key => $val) {
                    $x[] = ' '.$key.'="';
                    for ($i = 0; $i < strlen($val); $i++) {
                        $x[] = "|".ord(substr($val, $i, 1));
                    }
                    $x[] = '"';
                }
            }
            else {
                for ($i = 0; $i < strlen($attributes); $i++) {
                    $x[] = substr($attributes, $i, 1);
                }
            }
        }

        $x[] = '>';

        $temp = array();
        for ($i = 0; $i < strlen($title); $i++) {
            $ordinal = ord($title[$i]);
            if ($ordinal < 128) {
                $x[] = "|".$ordinal;
            }
            else {
                if (count($temp) == 0) {
                    $count = ($ordinal < 224) ? 2 : 3;
                }
                $temp[] = $ordinal;
                if (count($temp) == $count) {
                    $number = ($count == 3) ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64) : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);
                    $x[] = "|".$number;
                    $count = 1;
                    $temp = array();
                }
            }
        }

        $x[] = '<';
        $x[] = '/';
        $x[] = 'a';
        $x[] = '>';
        $x = array_reverse($x);

        $output = '<script type="text/javascript">//<![CDATA['."\n";
        $output .= 'var l=new Array();';

        $i = 0;
        foreach ($x as $val) {
            $output .= "l[".$i++."]='{$val}';";
        }

        $output .= 'for (var i=l.length-1; i>=0; i=i-1) {';
        $output .= 'if (l[i].substring(0,1)==\'|\') document.write("&#"+unescape(l[i].substring(1))+";");';
        $output .= 'else document.write(unescape(l[i]));}';
        $output .= "\n".'//]]></script>';

        return $output;
    }
}