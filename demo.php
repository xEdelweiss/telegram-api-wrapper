<?php

require './vendor/autoload.php';

$credentials = json_decode(file_get_contents('./credentials.json'));

$result = \TelegramApi\Api\Auth::sendCode('123123123', 5, $credentials->apiId, $credentials->apiHash, 'ua_UK');

dd($result);

//region Helpers
/**
 * @param array ...$arguments
 */
function dd(...$arguments)
{
    call_user_func_array('dump', $arguments);
    die;
}
//endregion