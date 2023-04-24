<?php


namespace Bytes\Tests\Common;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

trigger_deprecation('mrgoodbytes8667/test-common', '0.1.2', 'Since 0.1.2, switch to using Value Resolvers.');

/**
 * Trait TestParamConverterTrait
 * @package Bytes\Tests\Common
 *
 * Based on the SensioFrameworkBundle ParamConverter tests
 * @link https://github.com/sensiolabs/SensioFrameworkExtraBundle/blob/89d6f6218406d8c62349f0a28706563755332af0/src/Request/ParamConverter/DateTimeParamConverter.php
 */
trait TestParamConverterTrait
{
    /**
     * @param null $class
     * @param null $name
     * @param bool $optional
     * @return ParamConverter
     */
    public function createConfiguration($class = null, $name = null, bool $optional = false, array $options = [])
    {
        $config = $this
            ->getMockBuilder(ParamConverter::class)
            ->disableOriginalConstructor()
            ->getMock();

        if (null !== $name) {
            $config->expects($this->any())
                ->method('getName')
                ->willReturn($name);
        }
        if (null !== $class) {
            $config->expects($this->any())
                ->method('getClass')
                ->willReturn($class);
        }
        $config->method('isOptional')
            ->willReturn($optional);

        $config->method('getOptions')
            ->willReturn($options);

        return $config;
    }
}