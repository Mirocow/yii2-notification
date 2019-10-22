<?php

namespace mirocow\notification\helpers;

class ErrorHelper
{
    public static function message (\Exception $e)
    {
        return [
            'Error' => $e->getMessage(),
            'Code' => $e->getCode(),
            'File' => $e->getFile() . ': ' . $e->getLine(),
            'Trace' => $e->getTraceAsString(),
        ];
    }
}
