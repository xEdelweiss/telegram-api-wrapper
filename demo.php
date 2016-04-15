<?php

require './vendor/autoload.php';

// something goes here

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