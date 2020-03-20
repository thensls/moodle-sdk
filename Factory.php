<?php

namespace Nsls\Moodle;

use Nsls\Moodle\Http\Client;
use Nsls\Moodle\Rest\Courses;
use Nsls\Moodle\Rest\Groups;
use Nsls\Moodle\Rest\Rest;

/**
 * Class Factory
 *
 * @method Courses    courses()
 * @method Groups     groups()
 *
 * @package Nsls\Moodle
 */
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

    /**
     * Return an instance of a Rest class bases on method called.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return Rest
     */
    public function __call(string $name, array $arguments)
    {
        $restCall = 'Nsls\\Moodle\\Rest\\' . ucfirst($name);
        return new $restCall($this->client);
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