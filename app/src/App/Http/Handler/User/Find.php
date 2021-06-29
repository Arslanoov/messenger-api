<?php

declare(strict_types=1);

namespace App\Http\Handler\User;

use App\Exception\StartDialogWithYourself;
use App\Exception\UserNotFound;
use App\Http\Response\ResponseFactory;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use User\Model\Id;
use User\Model\Status;
use User\Model\UserInterface;
use User\ReadModel\UserFetcherInterface;

/**
 * Class Find
 * @package App\Http\Handler\User\Find
 * @Route(path="/users/find/{uuid}", defaults={"uuid"=""}, name="users.find", methods={"GET"})
 */
final class Find
{
    private UserFetcherInterface $users;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(UserFetcherInterface $users, ResponseFactory $response, Security $security)
    {
        $this->users = $users;
        $this->response = $response;
        $this->security = $security;
    }

    public function __invoke(string $uuid): mixed
    {
        /** @var UserInterface $currentUser */
        $currentUser = $this->security->getUser();
        $user = $this->users->searchOneByUuid(new Id($uuid));
        if (!$user || $user['status'] === Status::DRAFT) {
            throw new UserNotFound();
        }
        if ($user['username'] === $currentUser->getUsername()) {
            throw new StartDialogWithYourself();
        }

        return $this->response->json($user);
    }
}
