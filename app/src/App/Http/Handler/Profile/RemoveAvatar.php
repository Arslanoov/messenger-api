<?php

declare(strict_types=1);

namespace App\Http\Handler\Profile;

use App\Http\Response\ResponseFactory;
use App\Security\UserIdentity;
use App\Service\FileUploader;
use App\Service\UuidGenerator;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use User\Model\Username;
use User\Model\UserRepositoryInterface;
use User\UseCase\Avatar\Remove\Command;
use User\UseCase\Avatar\Remove\Handler;

/**
 * Class RemoveAvatar
 * @package App\Http\Handler\Profile
 * @Route(path="/profile/avatar/remove", name="profile.avatar.remove", methods={"DELETE"})
 * @OA\Delete(
 *     path="/profile/avatar/remove",
 *     tags={"Profile avatar remove"},
 *     @OA\Response(
 *         response=200,
 *         description="Success response"
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
final class RemoveAvatar
{
    private FileUploader $uploader;
    private UserRepositoryInterface $users;
    private Handler $handler;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(
        FileUploader $uploader,
        UserRepositoryInterface $users,
        Handler $handler,
        ResponseFactory $response,
        Security $security
    ) {
        $this->uploader = $uploader;
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

        $this->uploader->remove('avatar/', $user->avatar());

        $this->handler->handle(new Command($user->getUsername()->getValue()));

        return $this->response->json([], 204);
    }
}
