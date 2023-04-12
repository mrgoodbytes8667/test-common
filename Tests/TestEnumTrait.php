<?php

namespace Bytes\Tests\Common;

use BackedEnum;
use JetBrains\PhpStorm\Deprecated;


/**
 * Trait TestEnumTrait
 * @package Bytes\Tests\Common
 *
 * @method assertIsArray($actual, string $message = '')
 */
trait TestEnumTrait
{
    /**
     * Returns an array of all docblock-defined enum methods hydrated into instances of the class
     * @param BackedEnum|class-string<BackedEnum> $enum A class name or instance of the Enum class
     * @return BackedEnum[]
     */
    #[Deprecated('Since 0.1.0, use the enum cases() method instead', '%class%::cases()')]
    public static function extractAllFromEnum(BackedEnum|string $enum)
    {
        return $enum::cases();
    }

    /**
     * @param BackedEnum|string $class
     */
    public function coverEnum(BackedEnum|string $class)
    {
        $this->assertIsArray($class::values());
    }
}
