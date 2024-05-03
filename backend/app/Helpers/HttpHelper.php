<?php

namespace App\Helpers;

class HttpHelper
{

    /**
     * Validamos que el status code dado sea un status code válido
     */
    public static function checkCode(
        int $statusCode,
    ): bool
    {
        if ($statusCode >= 100 && $statusCode <= 599) return true;

        return false;
    }
}
