<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use User\Model\Status;

class UserIdentity implements UserInterface, EquatableInterface
{
    public function __construct(
        private string $id,
        private string $username,
        private string $password,
        private string $status
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function isDraft(): bool
    {
        return $this->status === Status::DRAFT;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }

        return
            $this->id === $user->id and
            $this->username === $user->username and
            $this->password === $user->password and
            $this->status === $user->status
        ;
    }
}
