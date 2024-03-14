<?php

namespace Src;

use Throwable;

class ErrorHandler
{
    public static function handleException(Throwable $exception)
    {
        echo json_encode([
            "status" => "ERROR",
            "errormessage" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine()
        ]);
    }

    public static function handleError($errno, $errorString, $errorFile, $errorLine)
    {
        throw new \ErrorException($errorString, 0, $errno, $errorFile, $errorLine);
    }
}