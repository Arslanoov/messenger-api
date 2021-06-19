<?php

declare(strict_types=1);

namespace App\Http\Handler\Admin\Users;

use App\Exception\AccessForbidden;
use App\Http\Response\ResponseFactory;
use App\Security\UserIdentity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use User\Model\Id;
use User\Model\Username;
use User\Model\UserRepositoryInterface;

/**
 * Class Show
 * @package App\Http\Handler\Admin\Show
 * @Route(path="/admin/user/{uuid}", name="admin.users.show", methods={"GET"})
 */
final class Show
{
    private UserRepositoryInterface $users;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(
        UserRepositoryInterface $users,
        ResponseFactory $response,
        Security $security
    ) {
        $this->users = $users;
        $this->response = $response;
        $this->security = $security;
    }

    public function __invoke(Request $request): mixed
    {
        /** @var UserIdentity $userIdentity */
        $userIdentity = $this->security->getUser();

        $user = $this->users->getByUsername(new Username($userIdentity->getUsername()));
        if (!$user->isAdmin()) {
            throw new AccessForbidden();
        }

        $uuid = (string) ($request->get('uuid') ?? '');
        $showUser = $this->users->getById(new Id($uuid));

        return $this->response->json([
            'uuid' => $showUser->getUuid()->getValue(),
            'username' => $showUser->getUsername()->getValue(),
            'status' => $showUser->getStatus()->getValue(),
            'role' => $showUser->role()->getValue(),
            'avatar' => $showUser->avatar(),
            'about' => $showUser->aboutMe(),
            'latestActivity' => $showUser->getLatestActivity()->format('d.m.Y H:i:s'),
            'messagesCount' => $showUser->getMessagesCount(),
        ]);
    }
}
