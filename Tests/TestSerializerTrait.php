<?php


namespace Bytes\Tests\Common;


use Bytes\EnumSerializerBundle\Serializer\Normalizer\EnumNormalizer;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorResolverInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ConstraintViolationListNormalizer;
use Symfony\Component\Serializer\Normalizer\DataUriNormalizer;
use Symfony\Component\Serializer\Normalizer\DateIntervalNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeZoneNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
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
     * @var ArrayCollection<class-string<NormalizerInterface|DenormalizerInterface>, NormalizerInterface|DenormalizerInterface>|null
     */
    protected ?ArrayCollection $normalizers = null;

    /**
     * @param bool $includeObjectNormalizer
     * @param array<NormalizerInterface|DenormalizerInterface> $prependNormalizers
     * @param array<NormalizerInterface|DenormalizerInterface> $appendNormalizers
     * @param bool $includeEnumNormalizer
     * @return Serializer
     */
    protected function createSerializer(bool $includeObjectNormalizer = true, array $prependNormalizers = [], array $appendNormalizers = [], bool $includeEnumNormalizer = true)
    {
        if(is_null($this->normalizers)) {
            $this->normalizers = new ArrayCollection();
        }
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
        $normalizers = $this->getNormalizers($includeObjectNormalizer, array_merge($prependNormalizers, [new UnwrappingDenormalizer()]), $appendNormalizers, includeEnumNormalizer: $includeEnumNormalizer);

        return new Serializer($normalizers, $encoders);
    }

    /**
     * @param bool $includeObjectNormalizer
     * @param array<NormalizerInterface|DenormalizerInterface> $prependNormalizers
     * @param array<NormalizerInterface|DenormalizerInterface> $appendNormalizers
     * @param bool $includeEnumNormalizer
     * @return array<NormalizerInterface|DenormalizerInterface>
     */
    protected function getNormalizers(bool $includeObjectNormalizer = true, array $prependNormalizers = [], array $appendNormalizers = [], bool $includeEnumNormalizer = true)
    {
        if(is_null($this->normalizers)) {
            $this->normalizers = new ArrayCollection();
        }
        foreach ($prependNormalizers as $normalizer) {
            $this->addNormalizer($normalizer);
        }
        $this->setupObjectNormalizerParts();

        $objectNormalizer = new ObjectNormalizer(classMetadataFactory: $this->classMetadataFactory,
            nameConverter: $this->metadataAwareNameConverter, propertyAccessor: $this->propertyAccessor,
            propertyTypeExtractor: $this->propertyInfo, classDiscriminatorResolver: $this->classDiscriminatorFromClassMetadata);
        if($includeEnumNormalizer) {
            $this->addNormalizer(new EnumNormalizer());
        }
        foreach ($appendNormalizers as $normalizer) {
            $this->addNormalizer($normalizer);
        }
        if ($includeObjectNormalizer) {
            $this->addNormalizer($objectNormalizer);
        }
        return array_values($this->normalizers->toArray());
    }

    /**
     * @param NormalizerInterface|DenormalizerInterface $normalizer
     * @return $this
     */
    protected function addNormalizer(NormalizerInterface|DenormalizerInterface $normalizer): self {
        if(is_null($this->normalizers)) {
            $this->normalizers = new ArrayCollection();
        }
        if(!$this->normalizers->containsKey($normalizer::class)) {
            $this->normalizers->set($normalizer::class, $normalizer);
        }

        return $this;
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
