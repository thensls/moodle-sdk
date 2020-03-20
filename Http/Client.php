<?php

namespace Nsls\Moodle\Http;

use InvalidArgumentException;
use Nsls\Moodle\Exception\MoodleClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Client
{

    /** @var string */
    private $token;

    /** @var string */
    private $serverUrl;

    /** @var HttpClient */
    public $httpClient;

    public function __construct(string $token = null,  string $serverUrl = null, HttpClient $httpClient = null, array $httpClientArguments = [])
    {
        $this->token = $token ?: getenv('MOODLE_TOKEN');
        $this->serverUrl = $serverUrl ?: getenv('MOODLE_SERVER_URL');
        $this->httpClient = $httpClient ?: HttpClient::create($httpClientArguments);
    }

    public function request(string $functionName, array $parameters = null): ?Response
    {
        $this->validateToken();
        $this->validateServerUrl();
        $query = $this->generateQuery($functionName, $parameters);

        try {
            $response = $this->httpClient->request(
                'GET',
                $this->serverUrl,
                [
                    'query' => $query,
                ]
            );
            return new Response($response);
        } catch (TransportExceptionInterface $e) {
            throw new MoodleClientException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function validateToken(): void
    {
        if (empty($this->token)) {
            throw new InvalidArgumentException('You must provide a Moodle token.');
        }
    }

    private function validateServerUrl(): void
    {
        if (empty($this->serverUrl)) {
            throw new InvalidArgumentException('You must provide a Moodle server URL.');
        }
        if (!filter_var($this->serverUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Moodle server URL needs to be a valid URL, given: %s',
                    $this->serverUrl)
            );
        }
    }

    private function generateQuery(string $functionName, ?array $parameters): array
    {
        $query = [
            'wstoken' => $this->token,
            'moodlewsrestformat' => 'json',
            'wsfunction' => $functionName,
        ];
        if (is_array($parameters)) {
            $query = array_merge($query, $parameters);
        }
        return $query;
    }
}