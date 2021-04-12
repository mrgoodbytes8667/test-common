<?php


namespace Bytes\Tests\Common\MockHttpClient;


use Symfony\Component\HttpClient\Response\MockResponse as BaseMockResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MockResponse
 * @package Bytes\Tests\Common\MockHttpClient
 */
class MockResponse extends BaseMockResponse
{
    /**
     * MockResponse constructor.
     * Needs the $mockResponse argument or the environment variable BYTES_MOCK_RESPONSE_CLASS for rate limits
     *
     * @param string|string[]|iterable $body The response body as a string or an iterable of strings,
     *                                       yielding an empty string simulates an idle timeout,
     *                                       exceptions are turned to TransportException
     * @param int $code
     * @param array $info = ResponseInterface::getInfo()
     * @param MockResponseHeaderInterface|null $responseHeaderClass
     *
     * @see ResponseInterface::getInfo() for possible info, e.g. "response_headers"
     */
    public function __construct($body = '', int $code = Response::HTTP_OK, array $info = [], ?MockResponseHeaderInterface $responseHeaderClass = null)
    {
        $info = self::normalizeInfo($info);

        if(is_null($responseHeaderClass)) {
            $class = getenv('BYTES_MOCK_RESPONSE_CLASS');
            if(!empty($class)) {
                $responseHeaderClass = new $class();
            }
        }
        if (!is_null($responseHeaderClass) && $responseHeaderClass instanceof MockResponseHeaderInterface) {
            $info['response_headers'] = array_merge($responseHeaderClass->getRateLimitArray(), $info['response_headers']);
        }

        if (!array_key_exists('http_code', $info)) {
            $info['http_code'] = $code;
        }
        parent::__construct($body, $info);
    }

    /**
     * Plug in response headers key if not exists
     * @param array $info
     * @return array
     */
    protected static function normalizeInfo(array $info = [])
    {
        if (!array_key_exists('response_headers', $info)) {
            $info['response_headers'] = [];
        }

        return $info;
    }
}