<?php

/**
 * Helper functions for working with files and directories.
 *
 * @version 1.3.1 (2013-05-28)
 * @author Denis Komlev <deniskomlev@hotmail.com>
 */
class KFile
{
    // ------------------------------------------------------------------------

    /**
     * Replace the directory separator to one used by system,
     * fix double slashes.
     */
    public static function fixSeparator($path, $ds = null)
    {
        if ($ds != '/' && $ds != '\\') {
            $ds = defined('DIRECTORY_SEPARATOR') ? DIRECTORY_SEPARATOR : '/';
        }
        return preg_replace('#[/\\\]+#', $ds, $path);
    }

    // ------------------------------------------------------------------------

    /**
     * Remove trailing and/or leading slashes.
     */
    public static function trimSlashes($path, $trimLeft = true, $trimRight = true)
    {
        if ($trimLeft)  $path = ltrim($path, '/\\');
        if ($trimRight) $path = rtrim($path, '/\\');
        return $path;
    }

    // ------------------------------------------------------------------------

    /**
     * Checks the file extension.
     *
     * @param string $file_name
     * @param string|array $extension Allowed extensions (may be separated by "|")
     * @return boolean
     */
    public static function matchExtension($file_name, $extension)
    {
        if (is_array($extension)) $extension = implode('|', $extension);
        $extension = explode('|', strtolower($extension));
        $file_extension = strtolower(self::extension($file_name));
        return in_array($file_extension, $extension);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the list of files in directory.
     *
     * @param string $path
     * @param string|array $types Allowed extensions (may be separated by "|")
     * @param boolean $only_files If true, directories will be excluded
     * @return array
     */
    public static function getFileList($path, $types = null, $only_files = true)
    {
        $path = self::trimSlashes($path, false);
        $file_list = glob(self::fixSeparator($path.'/*'));

        if (!is_array($file_list)) {
            return array();
        }

        foreach ($file_list as $key => $file_name) {
            if (($only_files && !is_file($file_name)) or
                ($types !== null && !self::matchExtension($file_name, $types)))
            {
                unset($file_list[$key]);
            }
        }

        return $file_list;
    }

    // ------------------------------------------------------------------------

    /**
     * Removes file extension from given file name.
     */
    public static function stripExtension($file_name)
    {
        return basename($file_name, '.'.self::extension($file_name));
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the file extension.
     */
    public static function extension($file_name)
    {
        return pathinfo($file_name, PATHINFO_EXTENSION);
    }

    // ------------------------------------------------------------------------

    /**
     * Serializes array and puts the data to a file.
     *
     * @param string $filename
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public static function putSerialized($filename, $data)
    {
        if (!is_array($data)) {
            throw new Exception(__CLASS__ . '::putSerialized() - Second argument should be an array.');
        }
        return (@file_put_contents($filename, serialize($data)) !== false);
    }

    // ------------------------------------------------------------------------

    /**
     * Unserializes data from file.
     *
     * @param string $filename
     * @return mixed Unserialized array or boolean FALSE.
     */
    public static function getUnserialized($filename)
    {
        $result = @unserialize(@file_get_contents($filename));
        return (is_array($result)) ? $result : false;
    }

    // ------------------------------------------------------------------------

    /**
     * Deletes file list.
     *
     * @param array $files
     * @return bool
     */
    public static function deleteFiles($files)
    {
        if (!is_array($files))
            throw new Exception(__CLASS__ . '::deleteFiles() - First argument should be an array.');

        $success = true;
        foreach ($files as $file) {
            if (is_file($file))
                $success = ($success && @unlink($file));
        }
        return $success;
    }

    // ------------------------------------------------------------------------

    public static function clearDir($path)
    {
        $files = self::getFileList($path, null, false);

        foreach ($files as $file) {
            if (is_dir($file)) self::removeDirRecursive($file);
            else @unlink($file);
        }
    }

    // ------------------------------------------------------------------------

    public static function removeDirRecursive($dir)
    {
        $files = self::getFileList($dir, null, false);

        foreach ($files as $file) {
            if (is_dir($file)) self::removeDirRecursive($file);
            else @unlink($file);
        }

        if (is_dir($dir)) @rmdir($dir);
    }
}