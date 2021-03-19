<?php

namespace Bytes\Tests\Common\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class ResponseStatusCodeSame
 * @package Bytes\Tests\Common\Constraint
 *
 * @see \Symfony\Component\HttpFoundation\Test\Constraint\ResponseStatusCodeSame
 */
final class ResponseStatusCodeSame extends Constraint
{
    private $statusCode;

    public function __construct(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return 'status code is '.$this->statusCode;
    }

    /**
     * @param ResponseInterface $response
     *
     * {@inheritdoc}
     */
    protected function matches($response): bool
    {
        return $this->statusCode === $response->getStatusCode();
    }

    /**
     * @param ResponseInterface $response
     *
     * {@inheritdoc}
     */
    protected function failureDescription($response): string
    {
        return 'the Response '.$this->toString();
    }
}
