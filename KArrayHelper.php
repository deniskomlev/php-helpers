<?php

/**
 * Helper class for working with arrays.
 *
 * @version 1.7.0 (2013-05-21)
 * @author Denis Komlev <deniskomlev@hotmail.com>
 */
class KArrayHelper
{
    private static $_sort_column = null;
    private static $_sort_direction = null;
    private static $_sort_method = null;

    // ------------------------------------------------------------------------

    /**
     * Get value from an array by key.
     *
     * @param array $array Source array
     * @param mixed $key Key of an array
     * @param mixed $default [optional] Default value to return
     * @return mixed
     */
    public static function element($array, $key, $default = null)
    {
        return (is_array($array) && array_key_exists($key, $array)) ? $array[$key] : $default;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns array of values from existing array.
     *
     * @param array $array Source array
     * @param array $keys Array of keys to return
     * @param mixed $default [optional] Default value
     * @param boolean $keep_null_keys Whether or not non-existing keys should be returned
     * @return array
     */
    public static function elements($array, $keys = array(), $default = null, $keep_null_keys = true)
    {
        $result = array();
        foreach ($keys as $key) {
            if (is_array($array) && array_key_exists($key, $array)) {
                $result[$key] = $array[$key];
            } else {
                if ($keep_null_keys) { $result[$key] = $default; }
            }
        }
        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Get value from an array using dot separated path.
     *
     * @param array $array Source array
     * @param mixed $path Keys path separated by delimeter
     * @param mixed $default [optional] Default value to return
     * @param string $delimeter [optional] Path delimeter
     * @return mixed
     */
    public static function path($array, $path, $default = null, $delimeter = '.')
    {
        if (!is_array($array)) {
            return $default;
        }

        $keys = explode($delimeter, $path);

        do {
            $key = array_shift($keys);

            if (isset($array[$key])) {
                if ($keys) {
                    $array = $array[$key];  // dig deeper
                }
                else {
                    return $array[$key];  // requested value is found
                }
            }
            else {
                break;  // unable to dig deeper
            }
        }
        while ($keys);

        // Unable to find requested path
        return $default;
    }

    // ------------------------------------------------------------------------

    /**
     * Override values of array 1 with values of same keys of array 2.
     *
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public static function override($array1, $array2)
    {
        foreach ($array1 as $key => $value)
        {
            $array1[$key] = Arr::element($array2, $key, $value);
        }
        return $array1;
    }

    // ------------------------------------------------------------------------

    /**
     * Randomizing array with ability to specify its length.
     *
     * @param array $array Source array
     * @param int $limit Maximum length of randomized array
     * @return array
     */
    public static function randomize($array, $limit = 0)
    {
        shuffle($array);
        if ($limit > 0) {
            $array = array_slice($array, 0, $limit);
        }
        return $array;
    }

    // ------------------------------------------------------------------------

    /**
     * Gets value from an array by key and remove this element from array.
     *
     * @param array $array
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public static function shiftElement(&$array, $key, $default = null)
    {
        $element = Arr::element($array, $key, $default);
        unset($array[$key]);
        return $element;
    }

    // ------------------------------------------------------------------------

    /**
     * Gets values from an array by keys and remove these elements from array.
     *
     * @param array $array
     * @param array $keys
     * @param mixed $default
     * @return mixed
     */
    public static function shiftElements(&$array, $keys, $default = null)
    {
        $elements = Arr::elements($array, $keys, $default);
        foreach ($keys as $key) {
            unset($array[$key]);
        }
        return $elements;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns flat array of values from input array.
     *
     * Note: Array keys will be not saved.
     *
     * @param array $array Input array
     * @return array New one-dimensional array
     */
    public static function flatten($array)
    {
        $result = array();

        if (is_array($array)) {
            $values = array_values($array);
            foreach ($values as $value) {
                if (is_array($value)) {
                    $result = array_merge($result, self::flatten($value));
                }
                else {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the neighbor array keys of given key.
     *
     * @param array $array
     * @param mixed $key
     * @return array
     */
    public static function getNearbyKeys(&$array, $key)
    {
        $prev_key = $next_key = null;
        $get_next_key = false;
        foreach ($array as $k => $v) {
            if ($get_next_key) {
                $next_key = $k;
                break;
            }
            if ($key == $k) {
                $get_next_key = true;
                continue;
            }
            $prev_key = $k;
        }
        return array($prev_key, $next_key);
    }

    // ------------------------------------------------------------------------

    /**
     * Swaps two array elements with preserving of their keys.
     *
     * @param array $array
     * @param mixed $key_1
     * @param mixed $key_2
     * @return bool
     */
    public static function swapElements(&$array, $key_1, $key_2)
    {
        if (is_array($array) && array_key_exists($key_1, $array) && array_key_exists($key_2, $array)) {
            $new_array = array();
            $element_1 = $array[$key_1];
            $element_2 = $array[$key_2];
            foreach ($array as $key => $element) {
                if ($key == $key_1) {
                    $new_array[$key_2] = $element_2;
                    continue;
                }
                if ($key == $key_2) {
                    $new_array[$key_1] = $element_1;
                    continue;
                }
                $new_array[$key] = $element;
            }
            $array = $new_array;
            return true;
        }
        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Build hierarchy tree from flat array.
     *
     * Input array should have following structure:
     *
     * array {
     *     [0] => array { [id] => 1, [parent] => 0 }
     *     [1] => array { [id] => 2, [parent] => 1 }
     *     [2] => array { [id] => 3, [parent] => 0 }
     *     [3] => array { [id] => 4, [parent] => 1 }
     * }
     *
     * Returning array from above example will be:
     *
     * array {
     *     [0] => array { [id] => 1, [parent] => 0, [children] => array {
     *         [1] => array { [id] => 2, [parent] => 1 }
     *         [3] => array { [id] => 4, [parent] => 1 }
     *     }
     *     [2] => array { [id] => 3, [parent] => 0 }
     * }
     *
     * @param array $input_array Input array
     * @param mixed $id_key Name of the array key references to record id
     * @param mixed $parent_key Name of the array key references to parent id
     * @param mixed $children_key Name of the array key for children records
     * @return array
     */
    public static function buildTree($input_array, $id_key, $parent_key, $children_key)
    {
        // Go throught each row in input array and for each row do the cycle
        // from beginning of same array for searching of parent row.
        // If the row inside of cycle contains children rows, the reference to it
        // is added to queue and the cycle is repeated until queue is not empty.
        // When parent row is found, move current row under children subarray
        // of parent row.

        foreach ($input_array as $input_key => $input_row)
        {
            // First queue element is always current array state
            $queue = array();
            $queue[] =& $input_array;

            // Start seeking parent row for current row.
            while (count($queue) > 0)
            {
                // Get the reference to first queued array.
                reset($queue);
                $queue_key = key($queue);
                $array =& $queue[$queue_key];

                // Walk throught queued array.
                foreach ($array as $key => $row)
                {
                    if ($input_row[$id_key] == $row[$id_key]) {
                        continue;  // skip itself
                    }

                    if ($input_row[$parent_key] == $row[$id_key]) {
                        // Found it. Copy current row under found one and remove current.
                        $array[$key][$children_key][$input_key] = $input_array[$input_key];
                        unset($input_array[$input_key]);
                        break 2;  // exit from while cycle
                    }

                    if (isset($row[$children_key])) {
                        // Add children subarray to queue.
                        $queue[] =& $array[$key][$children_key];
                    }
                }

                // Remove passed reference from queue.
                unset($queue[$queue_key]);
            }
        }

        return $input_array;
    }

    // ------------------------------------------------------------------------

    /**
     * Flattens hierarchical array with preserving of hierarchy order
     * and adding of hierarchy level.
     *
     * Input array:
     *
     * array {
     *     [0] => array { [id] => 1, [children] => array {
     *         [1] => array { [id] => 2 }
     *         [3] => array { [id] => 4 }
     *     }
     *     [2] => array { [id] => 3 }
     * }
     *
     * Result array:
     *
     * array {
     *     [0] => array { [id] => 1, [level] => 0 }
     *     [1] => array { [id] => 2, [level] => 1 }
     *     [2] => array { [id] => 4, [level] => 1 }
     *     [3] => array { [id] => 2, [level] => 0 }
     * }
     *
     * @param array $input_array Input array
     * @param mixed $children_key Name of the row key references to children records
     * @param mixed $level_key Name of the row key for hierarchy level
     * @return array
     */
    public static function flattenTree($input_array, $children_key, $level_key)
    {
        static $level = 0;

        $result = array();

        foreach ($input_array as $key => $row)
        {
            $_row = $row;
            $_row[$level_key] = $level;
            unset($_row[$children_key]);
            $result[] = $_row;

            if (!empty($row[$children_key])) {
                $level++;
                $result = array_merge($result, self::flatten_tree($row[$children_key], $children_key, $level_key));
            }
        }

        $level--;
        return $result;
    }

    // ------------------------------------------------------------------------

    /**
     * Sort two-dimensional array.
     *
     * @param array $table
     * @param string|int $column
     * @param string $direction
     * @param string $method
     * @return array
     */
    public static function sortTable($table, $column, $direction = 'asc', $method = 'numeric')
    {
        self::$_sort_column = $column;
        self::$_sort_direction = (strtolower($direction) == 'desc') ? 'desc' : 'asc';
        self::$_sort_method = (strtolower($method) == 'string') ? 'string' : 'numeric';

        usort($table, array(__CLASS__, '_sortTableCompare'));

        return $table;
    }

    // ------------------------------------------------------------------------

    /**
     * Compare function for sort_table method.
     *
     * @param  array  $a  Row of the first array.
     * @param  array  $b  Row of the second array.
     * @return int
     */
    private static function _sortTableCompare($a, $b)
    {
        $column = self::$_sort_column;
        $direction = self::$_sort_direction;
        $method = self::$_sort_method;

        if ($method == 'string') {
            $result = strcmp($a[$column], $b[$column]);
        } else {
            $result = $a[$column] - $b[$column];
        }

        $result = ($result == 0) ? 0 : (($result > 0) ? 1 : -1);
        return ($direction == 'asc') ? $result : -$result;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the values from a single column of the input array,
     * identified by the columnKey.
     *
     * Optionally, you may provide an indexKey to index the values in the
     * returned array by the values from the indexKey column in the input array.
     *
     * @param  array       $input      A multi-dimensional input array.
     * @param  int|string  $columnKey  The column of values to return.
     * @param  int|string  $indexKey   The column to use as keys for the returned array.
     * @return array
     */
    public static function column($input, $columnKey, $indexKey = null)
    {
        if (!is_array($input)) {
            return array();
        }
        $result = array();
        if ($indexKey === null) {
            foreach ($input as $i => $in) {
                if (is_array($in) && array_key_exists($columnKey, $in)) {
                    $result[] = $in[$columnKey];
                }
                unset($input[$i]);
            }
        } else {
            foreach ($input as $i => $in) {
                if (is_array($in)
                    && array_key_exists($columnKey, $in)
                    && array_key_exists($indexKey, $in))
                {
                    $result[$in[$indexKey]] = $in[$columnKey];
                }
                unset($input[$i]);
            }
        }
        return $result;
    }
}