<?php

namespace Bytes\Tests\Common\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class ResponseContentSame
 * @package Bytes\Tests\Common\Constraint
 */
final class ResponseContentSame extends Constraint
{

    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return sprintf('content is "%s"', $this->content);
    }

    /**
     * @param ResponseInterface $response
     *
     * {@inheritdoc}
     */
    protected function matches($response): bool
    {
        return $this->content === $response->getContent(false);
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
