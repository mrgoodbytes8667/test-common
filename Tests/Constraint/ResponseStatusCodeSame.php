<?php

namespace Bytes\Tests\Common\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class ResponseStatusCodeSame.
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

    public function toString(): string
    {
        return 'status code is '.$this->statusCode;
    }

    /**
     * @param ResponseInterface $response
     */
    protected function matches($response): bool
    {
        return $this->statusCode === $response->getStatusCode();
    }

    /**
     * @param ResponseInterface $response
     */
    protected function failureDescription($response): string
    {
        return 'the Response '.$this->toString();
    }
}
