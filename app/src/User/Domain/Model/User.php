<?php

declare(strict_types=1);

namespace Domain\Model\User;

use App\User\Exception\AlreadyActivated;

class User implements UserInterface
{
    private Id $uuid;
    private Username $username;
    private string $hash;
    private Status $status;

    public function __construct(Id $uuid, Username $username, string $hash, Status $status)
    {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->hash = $hash;
        $this->status = $status;
    }

    public function getUuid(): Id
    {
        return $this->uuid;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function activate(): void
    {
        if ($this->status->isActive()) {
            throw new AlreadyActivated();
        }

        $this->status = Status::active();
    }
}
