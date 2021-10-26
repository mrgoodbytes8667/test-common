<?php


namespace Bytes\Tests\Common;


use Bytes\EnumSerializerBundle\Serializer\Normalizer\EnumNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\Extractor\SerializerExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorResolverInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ConstraintViolationListNormalizer;
use Symfony\Component\Serializer\Normalizer\DataUriNormalizer;
use Symfony\Component\Serializer\Normalizer\DateIntervalNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeZoneNormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ProblemNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Trait TestSerializerTrait
 * @package Bytes\Tests\Common
 */
trait TestSerializerTrait
{
    use TestExtractorTrait;

    /**
     * @var NameConverterInterface
     */
    protected $metadataAwareNameConverter = null;

    /**
     * @var ClassDiscriminatorResolverInterface
     */
    protected $classDiscriminatorFromClassMetadata = null;

    /**
     * @param bool $includeObjectNormalizer
     * @param array $prependNormalizers
     * @param array $appendNormalizers
     * @return Serializer
     */
    protected function createSerializer(bool $includeObjectNormalizer = true, array $prependNormalizers = [], array $appendNormalizers = [])
    {
        if (empty($appendNormalizers)) {
            $appendNormalizers = [
                new ProblemNormalizer(),
                new JsonSerializableNormalizer(),
                new DateTimeNormalizer(),
                new ConstraintViolationListNormalizer(),
                new DateTimeZoneNormalizer(),
                new DateIntervalNormalizer(),
                new DataUriNormalizer(),
                new ArrayDenormalizer(),
            ];
        }

        $encoders = [new XmlEncoder(), new JsonEncoder(), new CsvEncoder()];
        $normalizers = $this->getNormalizers($includeObjectNormalizer, array_merge($prependNormalizers, [new UnwrappingDenormalizer()]), $appendNormalizers);

        return new Serializer($normalizers, $encoders);
    }

    /**
     * @param bool $includeObjectNormalizer
     * @param array $prependNormalizers
     * @param array $appendNormalizers
     * @return array
     */
    protected function getNormalizers(bool $includeObjectNormalizer = true, array $prependNormalizers = [], array $appendNormalizers = [])
    {
        $normalizers = $prependNormalizers;
        $this->setupObjectNormalizerParts();

        $objectNormalizer = new ObjectNormalizer($this->classMetadataFactory, $this->metadataAwareNameConverter, $this->propertyAccessor, $this->propertyInfo, $this->classDiscriminatorFromClassMetadata);
        $normalizers[] = new EnumNormalizer();
        foreach ($appendNormalizers as $normalizer) {
            $normalizers[] = $normalizer;
        }
        if ($includeObjectNormalizer) {
            $normalizers[] = $objectNormalizer;
        }
        return $normalizers;
    }

    /**
     * @param ClassMetadataFactoryInterface|null $classMetadataFactory
     * @param NameConverterInterface|null $nameConverter
     * @param PropertyTypeExtractorInterface|null $propertyTypeExtractor
     * @param ClassDiscriminatorResolverInterface|null $classDiscriminatorResolver
     */
    protected function setupObjectNormalizerParts(ClassMetadataFactoryInterface $classMetadataFactory = null, NameConverterInterface $nameConverter = null, PropertyTypeExtractorInterface $propertyTypeExtractor = null, ClassDiscriminatorResolverInterface $classDiscriminatorResolver = null)
    {
        $this->setupExtractorParts($classMetadataFactory, $propertyTypeExtractor);

        $this->metadataAwareNameConverter = $nameConverter ?? $this->nameConverter ?? new MetadataAwareNameConverter($this->classMetadataFactory);

        $this->classDiscriminatorFromClassMetadata = $classDiscriminatorResolver ?? $this->classDiscriminatorResolver ?? new ClassDiscriminatorFromClassMetadata($this->classMetadataFactory);
    }
}
