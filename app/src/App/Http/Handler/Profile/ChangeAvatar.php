<?php

declare(strict_types=1);

namespace App\Http\Handler\Profile;

use App\Exception\InvalidAvatar;
use App\Http\Response\ResponseFactory;
use App\Security\UserIdentity;
use App\Service\FileUploader;
use App\Service\UuidGenerator;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use User\Model\Username;
use User\Model\UserRepositoryInterface;
use User\UseCase\Avatar\Change\Command;
use User\UseCase\Avatar\Change\Handler;

/**
 * Class ChangeAvatar
 * @package App\Http\Handler\Profile
 * @Route(path="/profile/avatar/upload", name="profile.avatar.upload", methods={"POST"})
 * @OA\Post(
 *     path="/profile/avatar/upload",
 *     tags={"Profile avatar upload"},
 *     @OA\RequestBody(
*          @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="avatar", type="file")
 *         )
 *     ),
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
final class ChangeAvatar
{
    private FileUploader $uploader;
    private UserRepositoryInterface $users;
    private UuidGenerator $uuid;
    private Handler $handler;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(
        FileUploader $uploader,
        UuidGenerator $uuid,
        UserRepositoryInterface $users,
        Handler $handler,
        ResponseFactory $response,
        Security $security
    ) {
        $this->uploader = $uploader;
        $this->uuid = $uuid;
        $this->users = $users;
        $this->handler = $handler;
        $this->response = $response;
        $this->security = $security;
    }

    public function __invoke(Request $request): mixed
    {
        /** @var ?UploadedFile $avatar */
        $avatar = $request->files->get('avatar');
        if (!$avatar) {
            throw new InvalidAvatar();
        }

        /** @var UserIdentity $userIdentity */
        $userIdentity = $this->security->getUser();
        $user = $this->users->getByUsername(new Username($userIdentity->getUsername()));

        // TODO: Separate
        if (!in_array($avatar->getClientMimeType(), [
            'image/png',
            'image/jpeg'
        ])) {
            throw new InvalidAvatar();
        }

        $file = $this->uploader->upload($avatar, 'avatar/', $user->getUuid()->getValue());

        $this->handler->handle(new Command($user->getUsername()->getValue(), $file->name));

        return $this->response->json([
            'url' => $file->path . $file->name
        ]);
    }
}
