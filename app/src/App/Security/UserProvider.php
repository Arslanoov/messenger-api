<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use User\Infrastructure\ReadModel\User\AuthView;
use User\ReadModel\UserFetcherInterface;

class UserProvider implements UserProviderInterface
{
    private UserFetcherInterface $users;

    public function __construct(UserFetcherInterface $users)
    {
        $this->users = $users;
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        $user = $this->loadUser($username);
        return self::identityByUser($user, $username);
    }

    public function refreshUser(UserInterface $identity): UserInterface
    {
        if (!$identity instanceof UserIdentity) {
            throw new UnsupportedUserException('Invalid user class ' . get_class($identity));
        }

        $user = $this->loadUser($identity->getUsername());
        return self::identityByUser($user, $identity->getUsername());
    }

    public function supportsClass(string $class): bool
    {
        return $class === UserIdentity::class;
    }

    /**
     * @param string $username
     * @return AuthView
     */
    private function loadUser(string $username): AuthView
    {
        if ($user = $this->users->findForAuthByUsername($username)) {
            return $user;
        }

        throw new UsernameNotFoundException('');
    }

    private static function identityByUser(AuthView $user, string $username): UserIdentity
    {
        return new UserIdentity(
            $user->uuid,
            $user->username ?: $username,
            $user->hash ?: '',
            $user->status
        );
    }
}
