<?php

namespace Bytes\Tests\Common;

use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\HttpClient\Exception\RedirectionException;
use Symfony\Component\HttpClient\Exception\ServerException;
use function is_array;
use const JSON_BIGINT_AS_STRING;
use const JSON_ERROR_NONE;
use const JSON_THROW_ON_ERROR;
use const PHP_VERSION_ID;

/**
 * Duplicated portions of this class to prevent dependency changes, as of #891797cde5a91022fbf6f39e203046036245501b
 * @link https://github.com/symfony/http-client/blob/710f6faec98f0c55d9f034c64e1a0e47928e1c74/Response/CommonResponseTrait.php
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @license MIT
 */
trait CommonResponseTrait
{
    private $content;
    private $jsonData;

    /**
     * {@inheritdoc}
     */
    public function toArray(bool $throw = true): array
    {
        if ('' === $content = $this->getContent($throw)) {
            throw new JsonException('Response body is empty.');
        }

        if (null !== $this->jsonData) {
            return $this->jsonData;
        }

        try {
            $content = json_decode($content, true, 512, JSON_BIGINT_AS_STRING | (PHP_VERSION_ID >= 70300 ? JSON_THROW_ON_ERROR : 0));
        } catch (\JsonException $e) {
            throw new JsonException($e->getMessage() . sprintf(' for "%s".', $this->getInfo('url')), $e->getCode());
        }

        if (PHP_VERSION_ID < 70300 && JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonException(json_last_error_msg() . sprintf(' for "%s".', $this->getInfo('url')), json_last_error());
        }

        if (!is_array($content)) {
            throw new JsonException(sprintf('JSON content was expected to decode to an array, "%s" returned for "%s".', get_debug_type($content), $this->getInfo('url')));
        }

        if (null !== $this->content) {
            // Option "buffer" is true
            return $this->jsonData = $content;
        }

        return $content;
    }

    private function checkStatusCode()
    {
        $code = $this->getInfo('http_code');

        if (500 <= $code) {
            throw new ServerException($this);
        }

        if (400 <= $code) {
            throw new ClientException($this);
        }

        if (300 <= $code) {
            throw new RedirectionException($this);
        }
    }
}