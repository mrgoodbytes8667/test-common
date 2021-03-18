<?php

namespace Bytes\Tests\Common;

use Bytes\EnumSerializerBundle\Enums\Enum;
use ReflectionClass;
use ReflectionException;


/**
 * Trait TestEnumTrait
 * @package Bytes\Tests\Common
 */
trait TestEnumTrait
{
    /**
     * Returns an array of all docblock-defined enum methods hydrated into instances of the class
     * @param object|string $enum A class name or instance of the Enum class
     * @return Enum[]
     * @throws ReflectionException
     */
    public static function extractAllFromEnum($enum)
    {
        $reflectionClass = new ReflectionClass($enum);

        $docComment = $reflectionClass->getDocComment();

        preg_match_all('/@method\s+static\s+self\s+([\w_]+)\(\)/', $docComment, $matches);

        $definition = [];

        foreach ($matches[1] as $methodName) {
            $definition[] = $enum::make($methodName);
        }

        return $definition;
    }
}