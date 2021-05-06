<?php


namespace Bytes\Tests\Common;

use Bytes\Tests\Common\Constraint\DateIntervalSame;
use DateInterval;
use Exception;

/**
 * Trait TestDateIntervalTrait
 * @package Bytes\Tests\Common
 */
trait TestDateIntervalTrait
{

    /**
     * @param DateInterval|string $expected
     * @param DateInterval $actual
     * @param bool $skipDaysProperty
     * @param string $message
     * @throws Exception
     */
    public static function assertDateIntervalEquals($expected, DateInterval $actual, bool $skipDaysProperty = true, string $message = '')
    {
        self::assertThat($actual, new DateIntervalSame($expected, $skipDaysProperty), $message);
    }

    /**
     * @param DateInterval|string $expected
     * @param DateInterval $actual
     * @param bool $skipDaysProperty
     * @param string $message
     * @throws Exception
     */
    public static function assertDateIntervalNotEquals($expected, DateInterval $actual, bool $skipDaysProperty = true, string $message = '')
    {
        self::assertThat($actual, self::logicalNot(new DateIntervalSame($expected, $skipDaysProperty)), $message);
    }
}
