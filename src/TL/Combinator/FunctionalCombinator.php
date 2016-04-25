<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 25.4.16
 * Time: 19:34
 */

namespace TelegramApi\TL\Combinator;

use TelegramApi\TL\Combinator;

/**
 * Class FunctionalCombinator
 * aka Method
 *
 * @package TelegramApi\TL\Combinator
 */
class FunctionalCombinator extends Combinator
{
    protected function getFullId()
    {
        return $this->schema['method'];
    }

    protected function getResultTypeFullId()
    {
        return $this->schema['type'];
    }
}