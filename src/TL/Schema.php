<?php

namespace TelegramApi\TL;

use TelegramApi\TL\Combinator\AggregateTypeConstructor;
use TelegramApi\TL\Combinator\FunctionalCombinator;
use TelegramApi\TL\Combinator\Param;
use TelegramApi\Util;

class Schema
{
    /**
     * @var array
     */
    private $schema;

    /**
     * @var AggregateTypeConstructor[]
     */
    protected $aggregateTypes = [];

    /**
     * @var FunctionalCombinator[]
     */
    protected $functionalCombinators = [];

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
        $this->fetchAggregateTypes();
        $this->fetchFunctionalCombinators();
    }

    protected function fetchAggregateTypes()
    {
        $this->aggregateTypes = array_map(function($item) {
            return new AggregateTypeConstructor($item);
        }, $this->schema['constructors']);
    }

    protected function fetchFunctionalCombinators()
    {
        $this->functionalCombinators = array_map(function($item) {
            return new FunctionalCombinator($item);
        }, $this->schema['methods']);
    }

    /**
     * @param $filePath
     * @throws \Exception
     */
    public function exportToPhp($dirPath)
    {
        $baseNamespace = 'TelegramApi\\Api';
        $classes = $this->getCombinatorsByClasses();

        foreach ($classes as $class => $methods) {
            $file = [
                '<?php',
                '',
                "namespace {$baseNamespace};",
                '',
            ];

            $file[] = "class {$class} extends BaseClass {";

            /**
             * @var $combinator Combinator
             */
            foreach ($methods as $method => $combinator) {
                $serializedCombinator = serialize($combinator);

                $paramsPhpDoc = array_map(function(Param $param) {
                    return "\t * @param " . $param->getPhpDoc();
                }, $combinator->getParams());

                $paramsPhpDoc[] = "\t * @return " . $combinator->getResultType()->getPhpDocType();
                $paramsPhpDoc = implode(PHP_EOL, $paramsPhpDoc);

                $paramsPhp = implode(', ', array_map(function(Param $param) {
                    return $param->getPhpTypeHint();
                }, $combinator->getParams()));

                $file[] = '';
                $file[] = "\t/*";
                $file[] = $paramsPhpDoc;
                $file[] = "\t */";
                $file[] = "\tpublic static function {$method}({$paramsPhp}) {";
                $file[] = "\t\t\$combinator = unserialize('{$serializedCombinator}');";
                $file[] = "\t\treturn self::call(__FUNCTION__, func_get_args(), \$combinator);";
                $file[] = "\t}";
            }

            $file[] = '';
            $file[] = "}";

            file_put_contents($dirPath . $class . '.php', implode(PHP_EOL, $file));
        }
    }

    protected function getCombinatorsByClasses()
    {
        $classes = [];

        foreach ([$this->aggregateTypes, $this->functionalCombinators] as $items) {
            /**
             * @var $items Combinator[]
             */
            foreach ($items as $combinator) {
                $class = Util::camelCase($combinator->getNamespace(), true) ?: 'Root';
                $method = Util::camelCase($combinator->getId());

                if (!isset($classes[$class])) {
                    $classes[$class] = [];
                }

                if (isset($classes[$class][$method])) {
                    throw new \Exception('Method override?');
                }

                $classes[$class][$method] = $combinator;
            }
        }

        return $classes;
    }
}