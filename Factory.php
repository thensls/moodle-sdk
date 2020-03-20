<?php

namespace Nsls\Moodle;

use Nsls\Moodle\Http\Client;

class Factory
{

    /** @var Client */
    private $client;

    /**
     * Factory constructor.
     */
    public function __construct(string $token = null,  string $serverUrl = null, Client $client = null, array $httpClientArguments = [])
    {
        $this->client = $client ?: new Client($token, $serverUrl, null, $httpClientArguments);
    }

    public static function instantiate(string $token = null,  string $serverUrl = null, Client $client = null, array $httpClientArguments = []): Factory
    {
        return new static($token, $serverUrl, $client, $httpClientArguments);
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }


}