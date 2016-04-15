<?php

require './vendor/autoload.php';

// trying to understand TL

$schemaPath = './schema.json'; // is this last layer? (from https://core.telegram.org/schema/json)
$schemaContent = file_get_contents($schemaPath);

$schema = new \TelegramApi\TL\Schema($schemaContent);
$schema->saveToPhp();

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