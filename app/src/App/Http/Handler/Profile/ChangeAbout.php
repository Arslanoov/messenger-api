<?php

declare(strict_types=1);

namespace App\Http\Handler\Profile;

use App\Http\Response\ResponseFactory;
use App\Security\UserIdentity;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use User\Model\Username;
use User\Model\UserRepositoryInterface;
use User\UseCase\Change\About\Command as ChangeCommand;
use User\UseCase\Change\About\Handler as ChangeHandler;
use User\UseCase\Online\Command as OnlineCommand;
use User\UseCase\Online\Handler as OnlineHandler;

/**
 * Class ChangeAvatar
 * @package App\Http\Handler\Profile
 * @Route(path="/profile/change/about", name="profile.change.about", methods={"PATCH"})
 * @OA\Patch(
 *     path="/profile/change/about",
 *     tags={"Profile about change"},
 *     @OA\RequestBody(
*          @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="about", type="string", nullable=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=204,
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
final class ChangeAbout
{
    private UserRepositoryInterface $users;
    private ChangeHandler $handler;
    private OnlineHandler $onlineHandler;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(
        UserRepositoryInterface $users,
        ChangeHandler $handler,
        OnlineHandler $onlineHandler,
        ResponseFactory $response,
        Security $security
    ) {
        $this->users = $users;
        $this->handler = $handler;
        $this->onlineHandler = $onlineHandler;
        $this->response = $response;
        $this->security = $security;
    }

    public function __invoke(Request $request): mixed
    {
        $content = (string) $request->getContent();
        $body = (array) json_decode($content, true);

        $about = (string) $body['about'];

        /** @var UserIdentity $userIdentity */
        $userIdentity = $this->security->getUser();
        $user = $this->users->getByUsername(new Username($userIdentity->getUsername()));

        $this->handler->handle(new ChangeCommand($user->getUsername()->getValue(), $about));
        $this->onlineHandler->handle(new OnlineCommand($userIdentity->getUsername()));

        return $this->response->json([], 204);
    }
}
