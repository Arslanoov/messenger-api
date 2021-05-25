<?php

declare(strict_types=1);

namespace App\Http\Handler\User;

use App\Http\Response\ResponseFactory;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;
use User\ReadModel\UserFetcherInterface;

/**
 * Class Search
 * @package App\Http\Handler\User\Search
 * @Route(path="/users/search/{content}", defaults={"content"=""}, name="users.search", methods={"GET"})
 */
final class Search
{
    private UserFetcherInterface $users;
    private ResponseFactory $response;

    public function __construct(UserFetcherInterface $users, ResponseFactory $response)
    {
        $this->users = $users;
        $this->response = $response;
    }

    public function __invoke(string $content): mixed
    {
        $results = $this->users->searchByUsername($content);

        return $this->response->json([
            'items' => $results
        ]);
    }
}
