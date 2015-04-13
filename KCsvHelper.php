<?php

/**
 * Helper functions for working with CSV files and strings.
 *
 * @version 0.7.2 (2015-01-13)
 * @author Denis Komlev <deniskomlev@hotmail.com>
 */
class KCsvHelper
{

    /**
     * Makes the array from CSV file.
     *
     * @param  string $path      to the source CSV file
     * @param  string $delimeter [optional] field delimeter (default is comma)
     * @param  string $enclosure [optional] field enclosure (default is double quote)
     * @return array
     */
    public static function fileToArray($path, $delimeter = null, $enclosure = null)
    {
        $fileResource = fopen($path, 'r');

        $data = self::processFile($fileResource, $delimeter, $enclosure);

        fclose($fileResource);

        return $data;
    }




    /**
     * Makes the array from CSV string.
     *
     * @param string $inputString source CSV string
     * @param string $delimeter   [optional] field delimeter (default is comma)
     * @param string $enclosure   [optional] field enclosure (default is double quote)
     * @return array
     */
    public static function stringToArray($inputString, $delimeter = null, $enclosure = null)
    {
        $fileResource = tmpfile();
        fwrite($fileResource, $inputString);

        $data = self::processFile($fileResource, $delimeter, $enclosure);

        fclose($fileResource);

        return $data;
    }




    /**
     * Parses the already opened file by given handler.
     *
     * @param  resource $fileResource file handler
     * @param  string   $delimeter    [optional] Field delimeter (default is comma)
     * @param  string   $enclosure    [optional] Field enclosure (default is double quote)
     * @return array
     */
    public static function processFile(&$fileResource, $delimeter = null, $enclosure = null)
    {
        rewind($fileResource);  // reset the position of a file pointer

        $data = array();

        $delimeter = !empty($delimeter) ? $delimeter : ',';
        $enclosure = !empty($enclosure) ? $enclosure : '"';

        while (false !== ($row = fgetcsv($fileResource, 0, $delimeter, $enclosure))) {
            $data[] = $row;
        }

        return $data;
    }




    /**
     * Writes array to CSV file.
     *
     * @param array  $array     the CSV data as array
     * @param string $path      target file path
     * @param string $delimeter [optional] field delimeter (default is comma)
     * @param string $enclosure [optional] field enclosure (default is double quote)
     */
    public static function arrayToFile(array $array, $path, $delimeter = null, $enclosure = null)
    {
        $fileResource = fopen($path, 'w');

        $delimeter = !empty($delimeter) ? $delimeter : ',';
        $enclosure = !empty($enclosure) ? $enclosure : '"';

        foreach ($array as $row) {
            fputcsv($fileResource, $row, $delimeter, $enclosure);
        }

        fclose($fileResource);
    }

}
