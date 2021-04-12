<?php


namespace Bytes\Tests\Common\MockHttpClient;


use Symfony\Component\HttpFoundation\Response;

/**
 * Class MockEmptyResponse
 * @package Bytes\Tests\Common\MockHttpClient
 */
class MockEmptyResponse extends MockResponse
{
    /**
     * MockEmptyResponse constructor.
     * @param int $code
     * @param array $info = ResponseInterface::getInfo()
     *
     * @see ResponseInterface::getInfo() for possible info, e.g. "response_headers"
     */
    public function __construct(int $code = Response::HTTP_NO_CONTENT, array $info = [])
    {
        parent::__construct('', $code, $info);
    }
}