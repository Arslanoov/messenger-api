<?php

declare(strict_types=1);

namespace User\Model;

use Assert\AssertionFailedException;
use Doctrine\ORM\Mapping as ORM;
use Domain\AggregateRoot;
use Domain\EventsTrait;
use User\Exception\AlreadyActivated;
use User\Model\Event\UserSignedUp;

/**
 * Class User
 * @package User\Model
 * @ORM\Entity()
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"username"})
 * })
 */
class User implements UserInterface, AggregateRoot
{
    use EventsTrait;

    /**
     * @var Id
     * @ORM\Id()
     * @ORM\Column(type="user_user_id", length=128)
     */
    private Id $uuid;
    /**
     * @var Username
     * @ORM\Column(type="user_user_username", length=16)
     */
    private Username $username;
    /**
     * @var string
     * @ORM\Column(type="string",  length=128)
     */
    private string $hash;
    /**
     * @var Status
     * @ORM\Column(type="user_user_status", name="status", length=16)
     */
    private Status $status;

    public function __construct(Id $uuid, Username $username, string $hash, Status $status)
    {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->hash = $hash;
        $this->status = $status;
    }

    /**
     * @param string $username
     * @param string $hash
     * @return self
     * @throws AssertionFailedException
     */
    public static function signUp(string $username, string $hash): self
    {
        $user = new self(
            Id::generate(),
            new Username($username),
            $hash,
            Status::draft()
        );

        $user->recordEvent(new UserSignedUp($username));

        return $user;
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

    /**
     * @throws AlreadyActivated
     */
    public function activate(): void
    {
        if ($this->status->isActive()) {
            throw new AlreadyActivated();
        }

        $this->status = Status::active();
    }

    public function isActive(): bool
    {
        return $this->getStatus()->isActive();
    }

    public function isDraft(): bool
    {
        return $this->getStatus()->isDraft();
    }
}
