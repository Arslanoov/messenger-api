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
use User\Model\Status;
use User\Model\Username;
use User\Model\UserRepositoryInterface;
use User\UseCase\Draft\Command;
use User\UseCase\Draft\Handler;

/**
 * Class Draft
 * @package App\Http\Handler\Admin\Draft
 * @Route(path="/admin/user/draft/{uuid}", name="admin.user.draft", methods={"PATCH"})
 */
final class Draft
{
    private UserRepositoryInterface $users;
    private Handler $handler;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(
        UserRepositoryInterface $users,
        Handler $handler,
        ResponseFactory $response,
        Security $security
    ) {
        $this->users = $users;
        $this->handler = $handler;
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
        $manageUser = $this->users->getById(new Id($uuid));

        $this->handler->handle(new Command($manageUser->getUsername()->getValue()));

        return $this->response->json([
            'status' => Status::DRAFT
        ]);
    }
}
