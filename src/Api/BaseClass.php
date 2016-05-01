<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 30.4.16
 * Time: 19:21
 */

namespace TelegramApi\Api;


class BaseClass
{
    protected static function call($function, $arguments, $combinator)
    {
        dd([
            $function,
            $arguments,
            $combinator
        ]);
    }
}