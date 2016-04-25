<?php

namespace TelegramApi\TL;

class Schema
{
    private $schema;

    /**
     * Schema constructor.
     * @param string $schema
     * @param bool $isJson
     * @throws \Exception
     */
    public function __construct($schema, $isJson = true)
    {
        if (!$isJson) {
            throw new \Exception('Only JSON Schema is supported');
        }

        $this->schema = json_decode($schema, true);
    }

    public function getAggregateTypes()
    {

    }

    public function getFunctionalCombinators()
    {
        
    }
}