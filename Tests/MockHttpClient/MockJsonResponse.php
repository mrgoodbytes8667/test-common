<?php

namespace Bytes\Tests\Common\MockHttpClient;

use Symfony\Component\HttpFoundation\Response;

class MockJsonResponse extends MockResponse
{
    /**
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
        $info['response_headers']['Content-Type'] = 'application/json';
        parent::__construct($body, $code, $info, $responseHeaderClass);
    }
}