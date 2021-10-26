<?php

namespace Bytes\Tests\Common;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\Extractor\SerializerExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

trait TestExtractorTrait
{
    /**
     * @var ClassMetadataFactoryInterface
     */
    protected $classMetadataFactory = null;

    /**
     * @var SerializerExtractor
     */
    protected $serializerExtractor = null;

    /**
     * @var PhpDocExtractor
     */
    protected $phpDocExtractor = null;

    /**
     * @var ReflectionExtractor
     */
    protected $reflectionExtractor = null;

    /**
     * @var PropertyTypeExtractorInterface
     */
    protected $propertyInfo = null;

    /**
     * @var PropertyAccessorInterface
     */
    protected $propertyAccessor = null;

    /**
     * @param ClassMetadataFactoryInterface|null $classMetadataFactory
     * @param PropertyTypeExtractorInterface|null $propertyTypeExtractor
     */
    protected function setupExtractorParts(ClassMetadataFactoryInterface $classMetadataFactory = null, PropertyTypeExtractorInterface $propertyTypeExtractor = null)
    {
        $this->classMetadataFactory = $classMetadataFactory ?? $this->classMetadataFactory ?? new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $this->serializerExtractor = new SerializerExtractor($this->classMetadataFactory);
        $this->phpDocExtractor = new PhpDocExtractor();
        $this->reflectionExtractor = new ReflectionExtractor();

        $this->propertyInfo = $propertyTypeExtractor ?? $this->propertyTypeExtractor ?? new PropertyInfoExtractor(
                [$this->serializerExtractor, $this->reflectionExtractor],
                [$this->phpDocExtractor, $this->reflectionExtractor],
                [$this->phpDocExtractor],
                [$this->reflectionExtractor],
                [$this->reflectionExtractor]
            );

        $this->propertyAccessor = new PropertyAccessor();
    }
}