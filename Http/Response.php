<?php


namespace Nsls\Moodle\Http;

use Nsls\Moodle\Exception\MoodleClientException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Response implements ResponseInterface
{

    /** @var ResponseInterface */
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Get the response
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @inheritDoc
     */
    public function getContent(bool $throw = true): string
    {
        $content = $this->response->getContent($throw);
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
        $this->processNestedJson($content);
        return $content;
    }

    /**
     * @param bool $throw
     * @return array
     * @throws MoodleClientException
     */
    public function toArray(bool $throw = true): array
    {
        try {
            return json_decode($this->getContent($throw), true) ?: [];
        } catch (ClientExceptionInterface $e) {
            throw new MoodleClientException($e->getMessage(), $e->getCode(), $e);
        } catch (RedirectionExceptionInterface $e) {
            throw new MoodleClientException($e->getMessage(), $e->getCode(), $e);
        } catch (ServerExceptionInterface $e) {
            throw new MoodleClientException($e->getMessage(), $e->getCode(), $e);
        } catch (TransportExceptionInterface $e) {
            throw new MoodleClientException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(bool $throw = true): array
    {
        return $this->response->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function cancel(): void
    {
        $this->response->cancel();
    }

    /**
     * @inheritDoc
     */
    public function getInfo(string $type = null)
    {
       return $this->response->getInfo();
    }

    /**
     * Process Nested json.
     *
     * @param $content
     */
    private function processNestedJson(&$content): void
    {
        $decodedResponse = json_decode($content, true);
        if (is_array($decodedResponse)) {
            foreach ($decodedResponse as $key => $item) {
                if (array_key_exists('data', $item) && array_key_exists(
                        'post_process_nested_json',
                        $item
                    ) && $item['post_process_nested_json']) {
                    $decodedResponse[$key] = json_decode($item['data']);
                }
            }
            $content = json_encode($decodedResponse);
        }
    }

}