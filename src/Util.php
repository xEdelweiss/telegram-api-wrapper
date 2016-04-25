<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 25.4.16
 * Time: 19:39
 */

namespace TelegramApi;

class Util
{
    /**
     * @param $value
     * @return mixed
     */
    public static function camelCase($value, $toUpper = false)
    {
        if (empty($value)) {
            return $value;
        }

        $result = ucwords(str_replace(['-', '_'], ' ', $value));
        $result = str_replace(' ', '', $result);

        if ($toUpper) {
            return ucfirst($result);
        }

        return (strtolower($value[0]) == $value[0]) ? lcfirst($result) : $result;
    }
}