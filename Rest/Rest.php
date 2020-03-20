<?php

namespace Nsls\Moodle\Rest;

use Nsls\Moodle\Http\Client;

abstract class Rest
{

    /** @var Client */
    protected $client;

    /**
     * Rest constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

}
