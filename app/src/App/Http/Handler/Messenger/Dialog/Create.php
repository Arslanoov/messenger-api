<?php

declare(strict_types=1);

namespace App\Http\Handler\Messenger\Dialog;

use App\Http\Response\ResponseFactory;
use App\Security\UserIdentity;
use App\Service\UuidGenerator;
use App\Service\ValidatorInterface;
use DateInterval;
use DateTimeImmutable;
use Messenger\ReadModel\DialogFetcherInterface;
use Messenger\UseCase\Dialog\Create\Command as CreateCommand;
use Messenger\UseCase\Dialog\Create\Handler as CreateHandler;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use User\UseCase\Online\Command as OnlineCommand;
use User\UseCase\Online\Handler as OnlineHandler;

/**
 * Class Create
 * @package App\Http\Handler\Messenger\Dialog
 * @Route(path="/messenger/dialog/create", name="messenger.dialog.create", methods={"POST"})
 * @OA\Post(
 *     path="/messenger/dialog/create",
 *     tags={"Messenger dialog create"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             required={"with_author"},
 *             @OA\Property(property="with_author", type="string")
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
final class Create
{
    private CreateHandler $handler;
    private OnlineHandler $onlineHandler;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;
    private UuidGenerator $uuid;
    private DialogFetcherInterface $dialogs;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(
        CreateHandler $handler,
        OnlineHandler $onlineHandler,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        UuidGenerator $uuid,
        DialogFetcherInterface $dialogs,
        ResponseFactory $response,
        Security $security
    ) {
        $this->handler = $handler;
        $this->onlineHandler = $onlineHandler;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->uuid = $uuid;
        $this->dialogs = $dialogs;
        $this->response = $response;
        $this->security = $security;
    }

    public function __invoke(Request $request): mixed
    {
        $content = (string) $request->getContent();
        $body = (array) json_decode($content, true);

        $withAuthor = (string) ($body['with_author'] ?? '');

        /** @var UserIdentity $user */
        $user = $this->security->getUser();
        $dialogId = $this->uuid->uuid4();
        $command = new CreateCommand($dialogId, $user->getId(), $withAuthor);

        try {
            $this->validator->validateObjects([$command]);
        } catch (ValidationFailedException $e) {
            $data = $this->serializer->serialize($e->getViolations(), 'json');
            /** @var array<string, string | int | array> $decoded */
            $decoded = json_decode($data, true);
            return $this->response->json($decoded, 422);
        }

        $this->handler->handle($command);
        $this->onlineHandler->handle(new OnlineCommand($user->getUsername()));

        $dialog = $this->dialogs->findDialog($dialogId, $user->getId());
        $latestMessage = $this->dialogs->getLatestMessage((string) $dialog['uuid']);
        /* TODO: Extract */
        if ($latestMessage && mb_strlen((string) $latestMessage['content']) > 25) {
            $latestMessage['content'] = mb_substr((string) $latestMessage['content'], 0, 25) . '...';
        }

        // TODO: Move form controller
        if ($latestMessage) {
            if ($user->getId() === $latestMessage['author_id']) {
                $dialog['sentByMe'] = [
                    'isSent' => true,
                    'isRead' => $latestMessage['read_status'] === 'Read'
                ];
            } else {
                $dialog['sentByPartner'] = [
                    'isRead' => $latestMessage['read_status'] === 'Read'
                ];
            }
        }

        return $this->response->json([
            'uuid' => $dialog['uuid'],
            'partner' => [
                'uuid' => $dialog['partner_user_uuid'],
                'username' => $dialog['partner_user_username'],
                'avatarUrl' => $dialog['partner_user_avatar_url'],
                'aboutMe' => $dialog['partner_user_about_me'],
                'isOnline' => new DateTimeImmutable((string) $dialog['partner_latest_activity']) >
                    (new DateTimeImmutable())->add(new DateInterval("PT15M")),
            ],
            'messagesCount' => $dialog['messages_count'],
            'latestMessage' => $latestMessage
        ], 201);
    }
}
