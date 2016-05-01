<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 25.4.16
 * Time: 20:14
 */

namespace TelegramApi\TL;


use TelegramApi\TL\ReturnType\AggregateType;
use TelegramApi\TL\ReturnType\ArrayType;
use TelegramApi\TL\ReturnType\BuiltInType;
use TelegramApi\Util;

class ReturnType
{
    /**
     * @var array
     */
    protected static $builtInTypes = [
        'int',
        'long',
        'double',
        'string',
//        'null',
//        'Bool',
//        'True',
//        'False',
//        'Null'
    ];

    /**
     * @var string|null
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $id;

    /**
     * AggregateType constructor.
     */
    protected function __construct($fullId)
    {
        $this->namespace = $this->parseFullId($fullId)['namespace'];
        $this->id = $this->parseFullId($fullId)['id'];
    }

    public static function factory($fullId)
    {
        if (preg_match('/Vector<(?P<type>.+)>/ui', $fullId, $typeMatches)) {
            return new ArrayType(static::factory($typeMatches['type']));
        }

        return in_array($fullId, self::$builtInTypes)
            ? new BuiltInType($fullId)
            : new AggregateType($fullId);
    }

    /**
     * @return string|null
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

    public function getPhpTypeHint()
    {
        return preg_match('/[.!#]/ui', $this->getId())
            ? ''
            : $this->__toString();
    }

    public function getPhpDocType()
    {
        return preg_match('/[.!#]/ui', $this->getId())
            ? $this->getId()
            : $this->__toString();
    }

    public function __toString()
    {
        // $baseNamespace = 'TelegramApi\\Types\\';
        $baseNamespace = '';
        $returnTypeNamespace = $this->getNamespace()
            ? $this->getNamespace() . '\\'
            : '';

        $id = $this->getId();
        $type = $baseNamespace . Util::camelCase($returnTypeNamespace, true) . $id;

        return $type;
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