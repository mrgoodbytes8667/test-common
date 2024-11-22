<?php

namespace Bytes\Tests\Common;

use BackedEnum;


/**
 * @method assertIsArray($actual, string $message = '')
 */
trait TestEnumTrait
{
    /**
     * @param BackedEnum|string $class
     */
    public function coverEnum(BackedEnum|string $class)
    {
        $this->assertIsArray($class::values());
    }
}
