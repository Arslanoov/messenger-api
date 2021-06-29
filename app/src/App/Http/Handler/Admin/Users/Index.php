<?php

declare(strict_types=1);

namespace App\Http\Handler\Admin\Users;

use App\Exception\AccessForbidden;
use App\Http\Response\ResponseFactory;
use App\Security\UserIdentity;
use Exception\IncorrectPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use User\Model\Username;
use User\Model\UserRepositoryInterface;
use User\ReadModel\UserFetcherInterface;

/**
 * Class Index
 * @package App\Http\Handler\Admin\Users
 * @Route(path="/admin/users", name="admin.users", methods={"GET"})
 */
final class Index
{
    public const PER_PAGE = 10;

    private UserFetcherInterface $usersRead;
    private UserRepositoryInterface $users;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(
        UserFetcherInterface $usersRead,
        UserRepositoryInterface $users,
        ResponseFactory $response,
        Security $security
    ) {
        $this->usersRead = $usersRead;
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

        $page = (int) ($request->get('page') ?? 1);
        if ($page <= 0) {
            throw new IncorrectPage();
        }

        $users = $this->usersRead->findAll($page);

        return $this->response->json([
            'items' => $users['items'],
            'totalCount' => $users['totalCount'],
            'perPage' => self::PER_PAGE
        ]);
    }
}
