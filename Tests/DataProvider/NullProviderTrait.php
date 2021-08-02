<?php

namespace Bytes\Tests\Common\DataProvider;

use Generator;

/**
 *
 */
trait NullProviderTrait
{
    /**
     * @return Generator
     */
    public function provideNull()
    {
        yield [null];
    }
}