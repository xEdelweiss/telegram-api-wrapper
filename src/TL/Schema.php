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

    public function saveToPhp()
    {
        $this->saveMethodsToPhp($this->schema['constructors'], 'Types');
        $this->saveMethodsToPhp($this->schema['methods'], 'Methods');
    }

    public function saveMethodsToPhp($descriptions, $namespace)
    {
        $methodsClasses = $this->getMethodsByClasses($descriptions);
        $methodsFile = [
            '<?php',
        ];

        foreach ($methodsClasses as $class => $methods) {
            $fileEntry = [
                'namespace TelegramApi\\Api;',
                'class ' . ucfirst($class),
                '{',
            ];

            foreach ($methods as $method => $description) {
                $phpDoc = $this->convertParamsToPhp($description['params'] ?? [])['phpDoc'];
                $params = $this->convertParamsToPhp($description['params'] ?? [])['code'];

                $fileEntry[] = $phpDoc;
                $fileEntry[] = "  public static function {$method}($params)";
                $fileEntry[] = '  {';
                $fileEntry[] = '    // some code';
                $fileEntry[] = '  }';
            }

            $fileEntry[] = '}';

            $methodsFile[] = implode(PHP_EOL, $fileEntry);
        }

        file_put_contents(__DIR__ . '/../Api/' . $namespace . '.php', implode(PHP_EOL, $methodsFile));
    }

    public function getMethodsByClasses($descriptions)
    {
        $result = [];

        foreach ($descriptions as $methodDescription) {
            $methodFullName = $methodDescription['method'] ?? $methodDescription['predicate'] ?? 'null';

            if (is_null($methodFullName)) {
                throw new \Exception('Empty method/predicate');
            }

            if (strpos($methodFullName, '.') === FALSE) {
                $className = 'root';
                $methodName = $methodFullName;
            } else {
                list($className, $methodName) = explode('.', $methodFullName);
            }

            if (!isset($result[$className])) {
                $result[$className] = [];
            }

            if (isset($result[$className][$methodName])) {
                throw new \Exception('Method overload?');
            }

            $result[$className][$methodName] = $methodDescription;
        }

        return $result;
    }

    protected function convertParamsToPhp($params)
    {
        $result = [
            'code' => [],
            'phpDoc' => [],
        ];

        foreach ($params as $param) {
            $type = $param['type'];
            $docType = $param['type'];

            if ($type == '!X' || $type == '#') {
                $type = 'mixed';
                $docType = $type;
            } elseif (preg_match('/Vector<(?P<type>.+)>/ui', $type, $typeMatches)) {
                $type = 'array';
                $docType = 'Types\\' . $this->camelCase($typeMatches['type'], false) . '[]';
            } else {
                $type = 'Types\\' . $this->camelCase($param['type'], false);
                $docType = $type;
            }

            $name = $this->camelCase($param['name']);

            $result['code'][] = "{$type} \${$name}";
            $result['phpDoc'][] = " * @var {$docType} \${$name}";
        }

        return [
            'code' => implode(', ', $result['code']),
            'phpDoc' => '/**' . PHP_EOL . implode(PHP_EOL, $result['phpDoc']) . PHP_EOL . ' */',
        ];
    }

    /**
     * @param string $value
     * @param bool $toLower
     * @return string
     */
    private function camelCase($value, $toLower = true)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        $value = str_replace(' ', '', $value);
        return $toLower ? lcfirst($value) : $value;
    }
}