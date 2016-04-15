<?php

namespace TelegramApi;
use TelegramApi\Api\Auth;

/**
 * Class Connection
 * @package TelegramApi
 *
 * @author Michael Sverdlikovsky <xedelweiss@gmail.com>
 */
class Connection
{
    /**
     * @var string
     */
    private $apiId;

    /**
     * @var string
     */
    private $apiHash;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * Connection constructor.
     *
     * @param string $apiUrl
     * @param string $apiId
     * @param string $apiHash
     */
    public function __construct($apiUrl, $apiId, $apiHash)
    {
        $this->apiUrl = $apiUrl;
        $this->apiId = $apiId;
        $this->apiHash = $apiHash;
    }
}