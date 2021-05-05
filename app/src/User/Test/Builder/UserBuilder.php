<?php

declare(strict_types=1);

namespace User\Test\Builder;

use Assert\AssertionFailedException;
use DateTimeImmutable;
use User\Model\Id;
use User\Model\Status;
use User\Model\User;
use User\Model\Username;

final class UserBuilder
{
    private Id $id;
    private Username $username;
    private string $hash;
    private Status $status;
    private ?string $aboutMe;
    private DateTimeImmutable $latestActivity;
    private int $messagesCount;

    /**
     * UserBuilder constructor.
     * @throws AssertionFailedException
     */
    public function __construct()
    {
        $this->id = Id::generate();
        $this->username = new Username('User name');
        $this->hash = 'hash';
        $this->status = Status::draft();
        $this->aboutMe = 'About me';
        $this->latestActivity = new DateTimeImmutable();
        $this->messagesCount = 0;
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

    public function withAboutMe(?string $aboutMe): self
    {
        $builder = clone $this;
        $builder->aboutMe = $aboutMe;
        return $builder;
    }

    public function withLatestActivity(DateTimeImmutable $date): self
    {
        $builder = clone $this;
        $builder->latestActivity = $date;
        return $builder;
    }

    public function withMessagesCount(int $messagesCount): self
    {
        $builder = clone $this;
        $builder->messagesCount = $messagesCount;
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
            $this->status,
            $this->latestActivity,
            $this->messagesCount,
            $this->aboutMe,
        );
    }
}
