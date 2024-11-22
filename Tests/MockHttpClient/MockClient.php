<?php

namespace Bytes\Tests\Common\MockHttpClient;

use Illuminate\Support\Arr;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MockClient.
 */
class MockClient
{
    /**
     * @return MockHttpClient
     */
    public static function empty()
    {
        return static::client(new MockEmptyResponse());
    }

    /**
     * @param mixed ...$responseFactory
     *
     * @return MockHttpClient
     */
    public static function client(...$responseFactory)
    {
        // Flatten the array down by one layer if we're nested
        if (1 == count($responseFactory)) {
            $first = Arr::first($responseFactory);
            if (is_array($first)) {
                $responseFactory = $first;
            }
        }

        return new MockHttpClient($responseFactory);
    }

    /**
     * @return MockHttpClient
     */
    public static function emptyBadRequest()
    {
        return static::client(new MockEmptyResponse(Response::HTTP_BAD_REQUEST));
    }

    /**
     * @return MockHttpClient
     */
    public static function emptyError(int $code)
    {
        return static::client(new MockEmptyResponse($code));
    }

    /**
     * @param mixed ...$requests
     *
     * @return MockHttpClient
     */
    public static function requests(...$requests)
    {
        return static::client($requests);
    }
}
