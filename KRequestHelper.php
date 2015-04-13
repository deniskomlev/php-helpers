<?php

class KRequestHelper
{
    protected static $statusCodes = array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
    );

    // ------------------------------------------------------------------------

    public static function sendStatusHeader($statusCode)
    {
        if (!isset(self::$statusCodes[$statusCode])) {
            throw new Exception('Status code ' . $statusCode . ' is not implemented.');
        }
        $message =  self::$statusCodes[$statusCode];
        $statusHeader = 'HTTP/1.1 ' . $statusCode . ' ' . $message;
        header($statusHeader);
    }
}