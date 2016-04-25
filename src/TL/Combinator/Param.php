<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 25.4.16
 * Time: 20:10
 */

namespace TelegramApi\TL\Combinator;

use TelegramApi\TL\ReturnType;
use TelegramApi\Util;

class Param
{
    /**
     * @var array
     */
    protected $schema;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var ReturnType
     */
    protected $type;

    /**
     * Param constructor.
     *
     * @param array $paramSchema
     */
    public function __construct($paramSchema)
    {
        $this->schema = $paramSchema;
        $this->name = $this->extractName();
        $this->type = $this->extractType();
    }

    /**
     * @return string
     */
    protected function extractName()
    {
        return $this->schema['name'];
    }

    /**
     * @return ReturnType
     */
    protected function extractType()
    {
        return ReturnType::factory($this->schema['type']);
    }

    /**
     * @return array
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ReturnType
     */
    public function getType()
    {
        return $this->type;
    }
    
    public function getPhpDoc()
    {
        $name = Util::camelCase($this->getName());
        $type = $this->getType()->getPhpDocType();

        return "{$type} \${$name}";
    }

    public function getPhpTypeHint()
    {

        $name = Util::camelCase($this->getName());
        $type = $this->getType()->getPhpTypeHint();

        return "{$type} \${$name}";
    }
}