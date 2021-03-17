<?php


namespace Bytes\Tests\Common;


use Bytes\EnumSerializerBundle\Serializer\Normalizer\EnumNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\Extractor\SerializerExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
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
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $serializerExtractor = new SerializerExtractor($classMetadataFactory);
        $phpDocExtractor = new PhpDocExtractor();
        $reflectionExtractor = new ReflectionExtractor();

        // list: SerializerExtractor, ReflectionExtractor, DoctrineExtractor
        // type: Doctrine, PhpDoc, Reflection
        // description: PhpDoc
        // access: Doctrine, Reflection
        // init: Reflection
        $propertyInfo = new PropertyInfoExtractor(
            [$serializerExtractor, $reflectionExtractor],
            [$phpDocExtractor, $reflectionExtractor],
            [$phpDocExtractor],
            [$reflectionExtractor],
            [$reflectionExtractor]
        );

        $objectNormalizer = new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter, new PropertyAccessor(), $propertyInfo, new ClassDiscriminatorFromClassMetadata($classMetadataFactory));
        $normalizers[] = new EnumNormalizer();
        foreach ($appendNormalizers as $normalizer) {
            $normalizers[] = $normalizer;
        }
        if ($includeObjectNormalizer) {
            $normalizers[] = $objectNormalizer;
        }
        return $normalizers;
    }
}