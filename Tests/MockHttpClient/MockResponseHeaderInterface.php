<?php

namespace Bytes\Tests\Common\MockHttpClient;

/**
 * Interface MockResponseHeaderInterface.
 */
interface MockResponseHeaderInterface
{
    public function getRateLimitArray(): array;
}
