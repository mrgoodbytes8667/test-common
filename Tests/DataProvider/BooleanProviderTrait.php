<?php

namespace Bytes\Tests\Common\DataProvider;

use Generator;

/**
 *
 */
trait BooleanProviderTrait
{
    /**
     * @return Generator
     */
    public function provideBooleans()
    {
        yield [true];
        yield [false];
    }
}