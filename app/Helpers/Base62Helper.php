<?php

namespace App\Helpers;

class Base62Helper
{
    private static $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function encode($data)
    {
        $base = strlen(self::$characters);
        $value = hexdec(bin2hex($data));
        $encoded = '';

        while ($value >= $base) {
            $mod = $value % $base;
            $encoded = self::$characters[$mod] . $encoded;
            $value = intdiv($value, $base);
        }

        return self::$characters[$value] . $encoded;
    }

    public static function decode($data)
    {
        $base = strlen(self::$characters);
        $length = strlen($data);
        $decoded = 0;

        for ($i = 0; $i < $length; $i++) {
            $decoded = $decoded * $base + strpos(self::$characters, $data[$i]);
        }

        $hex = dechex($decoded);

        if (strlen($hex) % 2 != 0) {
            $hex = '0' . $hex;
        }

        return hex2bin($hex);
    }
}
