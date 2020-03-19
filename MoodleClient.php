<?php


namespace Nsls\Library\Moodle;

use HttpException;
use InvalidArgumentException;
use Nsls\Library\Moodle\Exception\MoodleClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class MoodleClient
{

    public const ALLOWED_RETURN_FORMATS = [
        'json',
        'xml',
        'array',
    ];

    /**
     * @var string|null
     */
    private $serverAddress;
    /**
     * @var string|null
     */
    private $token;
    /**
     * @var string
     */
    private $returnFormat;
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * Moodle constructor.
     *
     * @param string $returnFormat
     * @param array $httpClientArgument
     */
    public function __construct(string $returnFormat = 'array', array $httpClientArgument = [])
    {
        $this->serverAddress = getenv('MOODLE_SERVER_URL') ?: '';
        $this->token = getenv('MOODLE_TOKEN') ?: '';
        if (!in_array($returnFormat, self::ALLOWED_RETURN_FORMATS, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'The $return_format argument %s is invalid, it should be one of %s',
                    $returnFormat,
                    implode(',', self::ALLOWED_RETURN_FORMATS)
                )
            );
        }
        $this->returnFormat = $returnFormat;
        $this->httpClient = HttpClient::create($httpClientArgument);
    }

    public function getServerAddress(): ?string
    {
        return $this->serverAddress;
    }

    public function setServerAddress(?string $serverAddress): void
    {
        if (!filter_var($serverAddress, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(
                sprintf(
                    '$serverAddress needs to be a valid URL, given: %s',
                    $serverAddress)
            );
        }
        $this->serverAddress = $serverAddress;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getReturnFormat(): string
    {
        return $this->returnFormat;
    }

    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    public function request(string $functionName, array $parameters = null)
    {
        $query = [
            'wstoken' => $this->token,
            'moodlewsrestformat' => 'json',
            'wsfunction' => $functionName,
        ];
        if (is_array($parameters)) {
            $query = array_merge($query, $parameters);
        }
        $response = $this->httpClient->request(
            'GET',
            $this->getServerAddress(),
            [
                'query' => $query,
            ]
        );

        return $this->responseHandler($response);
    }

    private function responseHandler(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new HttpException('Response code was not 200', $statusCode);
        }
        $content = $response->getContent();
        $decodedResponse = json_decode($content, true);
        if (is_array($decodedResponse) && array_key_exists('exception', $decodedResponse)) {
            throw new MoodleClientException(
                sprintf(
                    "Something went wrong communicating with the Moodle API.\n Exception: %s\n Error code: %s\n Message: %s\n",
                    $decodedResponse['exception'],
                    $decodedResponse['errorcode'],
                    $decodedResponse['message']
                )
            );
        }
        return $this->getReturnFormat() === 'array' ? $decodedResponse : $content;
    }

}