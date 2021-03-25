<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Service\ValidatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

final class SymfonyValidator implements ValidatorInterface
{
    private SymfonyValidatorInterface $validator;
    private LoggerInterface $logger;

    public function __construct(SymfonyValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * @param array<object> $objects
     */
    public function validateObjects(array $objects): void
    {
        foreach ($objects as $object) {
            $this->validate($object);
        }
    }

    public function validate(object $object): void
    {
        $violations = $this->validator->validate($object);
        if ($violations->count() > 0) {
            $this->logger->warning('Validation errors', [
                'violations' => $violations
            ]);
            throw new ValidationFailedException('Validation failed', $violations);
        }
    }
}
