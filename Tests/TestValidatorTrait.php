<?php


namespace Bytes\Tests\Common;


use Illuminate\Support\Arr;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Trait TestValidatorTrait
 * @package Bytes\Tests\Common
 */
trait TestValidatorTrait
{
    /**
     * @var ValidatorInterface|RecursiveValidator
     */
    private $validator;

    /**
     * @return RecursiveValidator|ValidatorInterface
     */
    protected function createValidator()
    {
        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping(true)
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();
    }

    /**
     * @param array|mixed $validations
     * @return int
     * @throws ValidationFailedException
     */
    protected function validate($validations)
    {
        $validator = $this->validator ?? $this->createValidator();

        $validations = Arr::wrap($validations);

        foreach ($validations as $validation) {
            $violations = $validator->validate($validation);
            if (0 !== count($violations)) {
                throw new ValidationFailedException($validation, $violations);
            }
        }

        return count($validations);
    }
}