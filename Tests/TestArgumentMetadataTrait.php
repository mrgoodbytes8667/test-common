<?php


namespace Bytes\Tests\Common;


use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 *
 */
trait TestArgumentMetadataTrait
{
    /**
     * @param class-string|null $class
     * @param string|null $name
     * @param bool $isVariadic
     * @param array $attributes
     * @return ArgumentMetadata
     */
    public function createArgumentMetadata(?string $class = null, ?string $name = null, bool $isVariadic = false, array $attributes = []): ArgumentMetadata
    {
        $config = $this
            ->getMockBuilder(ArgumentMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();

        if (null !== $name) {
            $config->method('getName')
                ->willReturn($name);
        }
        if (null !== $class) {
            $config->method('getType')
                ->willReturn($class);
        }
        $config->method('isVariadic')
            ->willReturn($isVariadic);

        $config->method('getAttributes')
            ->willReturn($attributes);

        return $config;
    }
}
