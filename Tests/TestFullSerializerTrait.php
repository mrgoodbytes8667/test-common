<?php

namespace Bytes\Tests\Common;

use Symfony\Component\Serializer\SerializerInterface;

/**
 * Trait TestFullSerializerTrait.
 */
trait TestFullSerializerTrait
{
    use TestSerializerTrait;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @before
     */
    protected function setUpSerializer()
    {
        $this->serializer = $this->createSerializer();
    }

    /**
     * @after
     */
    protected function tearDownSerializer(): void
    {
        $this->serializer = null;
    }
}
