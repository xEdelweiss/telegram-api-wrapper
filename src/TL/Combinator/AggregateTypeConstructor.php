<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 25.4.16
 * Time: 19:33
 */

namespace TelegramApi\TL\Combinator;

use TelegramApi\TL\Combinator;

/**
 * Class AggregateTypeConstructor
 * aka Constructor
 *
 * @package TelegramApi\TL\Combinator
 */
class AggregateTypeConstructor extends Combinator
{
    protected function getFullId()
    {
        return $this->schema['predicate'];
    }
    
    protected function getResultTypeFullId()
    {
        return $this->schema['type'];
    }
}