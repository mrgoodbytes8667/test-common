<?php

namespace Bytes\Tests\Common;


use Symfony\Component\Serializer\SerializerInterface;

/**
 * Trait TestFullSerializerTrait
 * @package Bytes\Tests\Common
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