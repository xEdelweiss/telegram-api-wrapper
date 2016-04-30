<?php

require './vendor/autoload.php';

// trying to understand TL

$schemaPath = './schema.json'; // is this last layer? (from https://core.telegram.org/schema/json)
$schemaContent = file_get_contents($schemaPath);

// constructor
$sampleConstructorSchema = json_decode('{
    "id": "-1965358985",
    "predicate": "updates.difference",
    "params": [{
        "name": "new_messages",
        "type": "Vector<messages.Message>"
    }, {
        "name": "other_updates",
        "type": "Vector<Update>"
    }, {
        "name": "chats",
        "type": "Vector<Chat>"
    }, {
        "name": "users",
        "type": "Vector<User>"
    }, {
        "name": "state",
        "type": "updates.State"
    }],
    "type": "updates.Difference"
}', true);

// method
$sampleMethodSchema = json_decode('{
    "id": "-1209117380",
    "method": "photos.getUserPhotos",
    "params": [{
        "name": "user_id",
        "type": "InputUser"
    }, {
        "name": "offset",
        "type": "int"
    }, {
        "name": "max_id",
        "type": "int"
    }, {
        "name": "limit",
        "type": "!X"
    }],
    "type": "photos.Photos"
}', true);

//$item = new \TelegramApi\TL\Combinator\AggregateTypeConstructor($sampleConstructorSchema);
$item = new \TelegramApi\TL\Combinator\FunctionalCombinator($sampleMethodSchema);

/**
 * @var \TelegramApi\TL\Combinator\Param[] $params
 */
$params = $item->getParams();
foreach ($params as $param) {
    dump($param->getPhpTypeHint());
    dump($param->getPhpDoc());
    dump('---');
}
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