<?php

declare(strict_types=1);

namespace App\User\Test\Builder;

use Domain\Model\User\Id;
use Domain\Model\User\Status;
use Domain\Model\User\User;
use Domain\Model\User\Username;

class UserBuilder
{
    private Id $id;
    private Username $username;
    private string $hash;
    private Status $status;

    public function __construct()
    {
        $this->id = Id::generate();
        $this->username = new Username('User name');
        $this->hash = 'hash';
        $this->status = Status::draft();
    }

    public function withId(Id $id): self
    {
        $builder = clone $this;
        $builder->id = $id;
        return $builder;
    }

    public function withUsername(Username $username): self
    {
        $builder = clone $this;
        $builder->username = $username;
        return $builder;
    }

    public function withHash(string $hash): self
    {
        $builder = clone $this;
        $builder->hash = $hash;
        return $builder;
    }

    public function withStatus(Status $status): self
    {
        $builder = clone $this;
        $builder->status = $status;
        return $builder;
    }

    public function active(): self
    {
        return $this->withStatus(Status::active());
    }

    public function draft(): self
    {
        return $this->withStatus(Status::draft());
    }

    public function build(): User
    {
        return new User(
            $this->id,
            $this->username,
            $this->hash,
            $this->status
        );
    }
}
