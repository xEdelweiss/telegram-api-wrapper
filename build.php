<?php

require './vendor/autoload.php';

// trying to understand TL

$schemaPath = './schema.json'; // is this last layer? (from https://core.telegram.org/schema/json)
$schemaContent = file_get_contents($schemaPath);

$schema = new \TelegramApi\TL\Schema($schemaContent);
$schema->exportToPhp(__DIR__ . '/src/Api/');