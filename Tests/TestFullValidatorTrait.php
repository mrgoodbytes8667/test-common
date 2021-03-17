<?php

namespace Bytes\Tests\Common;


/**
 * Trait TestFullValidatorTrait
 * @package Bytes\Tests\Common
 */
trait TestFullValidatorTrait
{
    use TestValidatorTrait;

    /**
     * @before
     */
    protected function setUpValidator()
    {
        $this->validator = $this->createValidator();
    }

    /**
     * @after
     */
    protected function tearDownValidator(): void
    {
        $this->validator = null;
    }
}