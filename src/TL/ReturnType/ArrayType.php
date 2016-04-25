<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 25.4.16
 * Time: 20:26
 */

namespace TelegramApi\TL\ReturnType;

use TelegramApi\TL\ReturnType;

class ArrayType extends ReturnType
{
    /**
     * @var ReturnType
     */
    protected $itemType;

    public function __construct(ReturnType $itemType)
    {
        $this->itemType = $itemType;
    }

    /**
     * @return ReturnType
     */
    public function getItemType()
    {
        return $this->itemType;
    }

    /**
     * @return null|string
     */
    public function getNamespace()
    {
        return $this->itemType->getNamespace();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->itemType->getId();
    }

}