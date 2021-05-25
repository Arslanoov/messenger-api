<?php

declare(strict_types=1);

namespace App\Http\Handler\User;

use App\Exception\UserNotFound;
use App\Http\Response\ResponseFactory;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;
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

    public function __construct(UserFetcherInterface $users, ResponseFactory $response)
    {
        $this->users = $users;
        $this->response = $response;
    }

    public function __invoke(string $uuid): mixed
    {
        $user = $this->users->searchOneByUuid($uuid);
        if (!$user) {
            throw new UserNotFound();
        }

        return $this->response->json($user);
    }
}
