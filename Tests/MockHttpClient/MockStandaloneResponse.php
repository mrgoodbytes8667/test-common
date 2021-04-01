<?php


namespace Bytes\Tests\Common\MockHttpClient;


use Symfony\Component\HttpClient\Response\CommonResponseTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class MockStandaloneResponse
 * A helper similar to the MockResponse used with MockHttpClient, but doesn't require MockHttpClient
 * @package Bytes\DiscordBundle\Tests\MockHttpClient
 */
class MockStandaloneResponse implements ResponseInterface
{
    use CommonResponseTrait;

    /**
     * @var array
     */
    public $info;

    /**
     * @var int
     */
    public $statusCode;

    /**
     * @var array
     */
    public $headers;

    /**
     * MockStandaloneResponse constructor.
     * @param null $content
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct($content = null, int $statusCode = Response::HTTP_OK, array $headers = [])
    {

        $this->info = [
            'cancelled' => false,
            'error' => null,
            'http_code' => $statusCode,
            'http_method' => 'GET',
            'redirection_count' => 0,
            'redirect_url' => null,
            'response_headers' => [],
            'start_time' => (float)1,
            'url' => '',
            'user_data' => null,
        ];

        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * Gets the HTTP headers of the response.
     *
     * @param bool $throw Whether an exception should be thrown on 3/4/5xx status codes
     *
     * @return string[][] The headers of the response keyed by header names in lowercase
     *
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when $throw is true and the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx when $throw is true
     * @throws ServerExceptionInterface      On a 5xx when $throw is true
     */
    public function getHeaders(bool $throw = true): array
    {
        // TODO: Implement getHeaders() method.
    }

    /**
     * Gets the response body as a string.
     *
     * @param bool $throw Whether an exception should be thrown on 3/4/5xx status codes
     *
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when $throw is true and the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx when $throw is true
     * @throws ServerExceptionInterface      On a 5xx when $throw is true
     */
    public function getContent(bool $throw = true): string
    {
        if ($throw) {
            $this->checkStatusCode();
        }

        if (is_callable($this->content)) {
            $func = $this->content;
            $content = $func();
        }

        return $content ?? $this->content ?? '';
    }

    /**
     * Gets the HTTP status code of the response.
     *
     * @throws TransportExceptionInterface when a network error occurs
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Closes the response stream and all related buffers.
     *
     * No further chunk will be yielded after this method has been called.
     */
    public function cancel(): void
    {
        // TODO: Implement cancel() method.
    }

    /**
     * Returns info coming from the transport layer.
     *
     * This method SHOULD NOT throw any ExceptionInterface and SHOULD be non-blocking.
     * The returned info is "live": it can be empty and can change from one call to
     * another, as the request/response progresses.
     *
     * The following info MUST be returned:
     *  - canceled (bool) - true if the response was canceled using ResponseInterface::cancel(), false otherwise
     *  - error (string|null) - the error message when the transfer was aborted, null otherwise
     *  - http_code (int) - the last response code or 0 when it is not known yet
     *  - http_method (string) - the HTTP verb of the last request
     *  - redirect_count (int) - the number of redirects followed while executing the request
     *  - redirect_url (string|null) - the resolved location of redirect responses, null otherwise
     *  - response_headers (array) - an array modelled after the special $http_response_header variable
     *  - start_time (float) - the time when the request was sent or 0.0 when it's pending
     *  - url (string) - the last effective URL of the request
     *  - user_data (mixed|null) - the value of the "user_data" request option, null if not set
     *
     * When the "capture_peer_cert_chain" option is true, the "peer_certificate_chain"
     * attribute SHOULD list the peer certificates as an array of OpenSSL X.509 resources.
     *
     * Other info SHOULD be named after curl_getinfo()'s associative return value.
     *
     * @return array|mixed|null An array of all available info, or one of them when $type is
     *                          provided, or null when an unsupported type is requested
     */
    public function getInfo(string $type = null)
    {
        if (!array_key_exists($type, $this->info)) {
            return null;
        }
        return $this->info[$type];
    }

    /**
     * Closes the response and all its network handles.
     */
    protected function close(): void
    {
        // TODO: Implement close() method.
    }
}