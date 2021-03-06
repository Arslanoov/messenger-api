<?php

declare(strict_types=1);

namespace User\Model;

use Assert\AssertionFailedException;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Domain\AggregateRoot;
use Domain\EventsTrait;
use Domain\Validation\DomainLogicAssertion;
use User\Exception\AlreadyActivated;
use User\Exception\AlreadyDeactivated;
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
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="latest_activity")
     */
    private DateTimeImmutable $latestActivity;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private int $messagesCount;
    /**
     * @var ?string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $aboutMe;
    /**
     * @var ?string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $avatarUrl;
    /**
     * @var Role
     * @ORM\Column(type="user_user_role", name="role", options={"default": "User"}, length=16)
     */
    private Role $role;

    public function __construct(
        Id $uuid,
        Username $username,
        string $hash,
        Status $status,
        DateTimeImmutable $latestActivity,
        Role $role,
        int $messagesCount = 0,
        ?string $aboutMe = null,
        ?string $avatarUrl = null
    ) {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->hash = $hash;
        $this->status = $status;
        $this->latestActivity = $latestActivity;
        $this->role = $role;
        $this->messagesCount = $messagesCount;
        $this->aboutMe = $aboutMe;
        $this->avatarUrl = $avatarUrl;
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
            Status::draft(),
            new DateTimeImmutable(),
            Role::user()
        );

        $user->recordEvent(new UserSignedUp($username));

        return $user;
    }

    /**
     * @param string $username
     * @param string $hash
     * @return self
     * @throws AssertionFailedException
     */
    public static function admin(string $username, string $hash): self
    {
        $user = new self(
            Id::generate(),
            new Username($username),
            $hash,
            Status::active(),
            new DateTimeImmutable(),
            Role::admin()
        );

        return $user;
    }

    /**
     * @param string $id
     * @param string $username
     * @param string $hash
     * @return self
     * @throws AssertionFailedException
     */
    public static function signUpWithId(string $id, string $username, string $hash): self
    {
        $user = new self(
            new Id($id),
            new Username($username),
            $hash,
            Status::active(),
            new DateTimeImmutable(),
            Role::user()
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

    public function aboutMe(): ?string
    {
        return $this->aboutMe;
    }

    public function role(): Role
    {
        return $this->role;
    }

    public function getLatestActivity(): DateTimeImmutable
    {
        return $this->latestActivity;
    }

    public function isOnline(DateTimeImmutable $now, DateInterval $onlineTimeout): bool
    {
        return $this->latestActivity->add($onlineTimeout) >= $now;
    }

    public function getMessagesCount(): int
    {
        return $this->messagesCount;
    }

    public function avatar(): ?string
    {
        return $this->avatarUrl;
    }

    public function changeAvatar(string $url): void
    {
        $this->avatarUrl = $url;
    }

    public function removeAboutInfo(): void
    {
        $this->aboutMe = null;
    }

    public function changeAboutInfo(?string $info): void
    {
        DomainLogicAssertion::maxLength($info, 255, 'Too long about me information');
        $this->aboutMe = $info;
    }

    public function changeRole(Role $role): void
    {
        $this->role = $role;
    }

    public function makeAdmin(): void
    {
        $this->role = Role::admin();
    }

    public function fire(): void
    {
        $this->role = Role::user();
    }

    public function removeAvatar(): void
    {
        $this->avatarUrl = null;
    }

    public function addAction(DateTimeImmutable $date): void
    {
        $this->latestActivity = $date;
    }

    /**
     * @throws AlreadyDeactivated
     */
    public function deactivate(): void
    {
        if ($this->status->isDraft()) {
            throw new AlreadyDeactivated();
        }

        $this->status = Status::draft();
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

    public function isUser(): bool
    {
        return $this->role->isUser();
    }

    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }
}
