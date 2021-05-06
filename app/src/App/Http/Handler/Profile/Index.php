<?php

declare(strict_types=1);

namespace App\Http\Handler\Profile;

use App\Http\Response\ResponseFactory;
use App\Security\UserIdentity;
use DateInterval;
use DateTimeImmutable;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use User\Model\Username;
use User\Model\UserRepositoryInterface;

/**
 * Class Index
 * @package App\Http\Handler\Profile\Index
 * @Route(path="/profile", name="profile", methods={"GET"})
 * @OA\Get(
 *     path="/profile",
 *     tags={"Profile"},
 *     @OA\Response(
 *         response=200,
 *         description="Success response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="uuid", type="string"),
 *             @OA\Property(property="username", type="string"),
 *             @OA\Property(property="aboutMe", type="string"),
 *             @OA\Property(property="isOnline", type="boolean"),
 *             @OA\Property(property="messagesCount", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Errors",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", nullable=true)
 *         )
 *     ),
 *     security={{"oauth2": {"common"}}}
 *   )
 * )
 */
final class Index
{
    private UserRepositoryInterface $users;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(UserRepositoryInterface $users, ResponseFactory $response, Security $security)
    {
        $this->users = $users;
        $this->response = $response;
        $this->security = $security;
    }

    public function __invoke(): mixed
    {
        /** @var UserIdentity $userIdentity */
        $userIdentity = $this->security->getUser();

        $user = $this->users->getByUsername(new Username($userIdentity->getUsername()));

        return $this->response->json([
            'uuid' => $user->getUuid()->getValue(),
            'username' => $user->getUsername()->getValue(),
            'aboutMe' => $user->aboutMe(),
            // TODO: DI Date interval
            'isOnline' => $user->isOnline(new DateTimeImmutable(), new DateInterval("PT15M")),
            'messagesCount' => $user->getMessagesCount(),
            // TODO: Add env
            'avatar' => 'http://localhost:8082/avatar/' . $user->avatar()
        ]);
    }
}
