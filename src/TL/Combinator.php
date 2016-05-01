<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 25.4.16
 * Time: 19:28
 */

namespace TelegramApi\TL;

use TelegramApi\TL\Combinator\Param;
use TelegramApi\TL\ReturnType\AggregateType;

abstract class Combinator
{
    /**
     * @var array
     */
    protected $schema;

    /**
     * @var string|null
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var Param[]
     */
    protected $params;

    /**
     * @var AggregateType
     */
    protected $resultType;

    /**
     * Combinator constructor.
     * 
     * @param array $itemSchema
     */
    public function __construct($itemSchema)
    {
        $this->schema = $itemSchema;
        $this->namespace = $this->extractNamespace();
        $this->id = $this->extractId();
        $this->params = $this->extractParams();
        $this->resultType = $this->extractResultType();
    }

    /**
     * @return string
     */
    abstract protected function getFullId();

    /**
     * @return string
     */
    abstract protected function getResultTypeFullId();

    /**
     * @return string|null
     */
    protected function extractNamespace()
    {
        return $this->parseFullId($this->getFullId())['namespace'];
    }

    /**
     * @return string
     */
    protected function extractId()
    {
        return $this->parseFullId($this->getFullId())['id'];
    }

    /**
     * @return Param[]
     */
    protected function extractParams()
    {
        $result = [];

        foreach ($this->schema['params'] as $param) {
            $result[] = new Param($param);
        }

        return $result;
    }

    /**
     * @return AggregateType
     */
    protected function extractResultType()
    {
        return ReturnType::factory($this->getResultTypeFullId());
    }

    /**
     * @return array
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @return null|string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Param[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return AggregateType
     */
    public function getResultType()
    {
        return $this->resultType;
    }

    protected function parseFullId($fullId)
    {
        if (strpos($fullId, '.') === FALSE) {
            $fullId = '.' . $fullId;
        }

        list($namespace, $id) = explode('.', $fullId);

        return [
            'namespace' => $namespace !== '' ? $namespace : null,
            'id' => $id,
        ];
    }
}