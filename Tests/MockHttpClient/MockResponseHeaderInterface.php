<?php


namespace Bytes\Tests\Common\MockHttpClient;


/**
 * Interface MockResponseHeaderInterface
 * @package Bytes\Tests\Common\MockHttpClient
 */
interface MockResponseHeaderInterface
{
    /**
     * @return array
     */
    public function getRateLimitArray(): array;
}
