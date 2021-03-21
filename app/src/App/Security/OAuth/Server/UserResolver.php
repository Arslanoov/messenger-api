<?php

declare(strict_types=1);

namespace App\Security\OAuth\Server;

use App\Exception\WrongCredentials;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Trikoder\Bundle\OAuth2Bundle\Event\UserResolveEvent;
use Trikoder\Bundle\OAuth2Bundle\OAuth2Events;
use User\Service\PasswordValidatorInterface;

final class UserResolver implements EventSubscriberInterface
{
    private UserProviderInterface $userProvider;
    private PasswordValidatorInterface $validator;

    public function __construct(UserProviderInterface $userProvider, PasswordValidatorInterface $validator)
    {
        $this->userProvider = $userProvider;
        $this->validator = $validator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OAuth2Events::USER_RESOLVE => 'onUserResolve',
        ];
    }

    public function onUserResolve(UserResolveEvent $event): void
    {
        /** @var UserInterface | null $user */
        $user = $this->userProvider->loadUserByUsername($event->getUsername());

        if (null === $user) {
            return;
        }

        if (!$user->getPassword()) {
            return;
        }

        try {
            $this->validator->validate($event->getPassword(), $user->getPassword() ?? '');
        } catch (WrongCredentials) {
            return;
        }

        $event->setUser($user);
    }
}
