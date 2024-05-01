<?php

namespace App\Http\Resources;

class CheckNullOrEmptyValues
{
    /**
     * Filtramos valores nulos de manera recursiva
     */
    public static function check(
        array $data,
    ): array {

        $result = [];
        foreach ($data as $key => $value) {
            if ($value !== null && $value !== '' && $value !== []) {
                $result[$key] = $value;
            }

            if (is_array($value)) {
                $result[$key] = self::check($value);
            }
        }

        return $result;
    }
}
