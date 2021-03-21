<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

final class NotActivated extends AccessForbidden
{
    private ?UserInterface $user = null;

    public function __construct(
        string $message = "Account is not activated",
        int $code = 403,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }
}
