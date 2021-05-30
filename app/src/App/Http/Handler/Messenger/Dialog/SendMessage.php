<?php

declare(strict_types=1);

namespace App\Http\Handler\Messenger\Dialog;

use App\Http\Response\ResponseFactory;
use App\Security\UserIdentity;
use App\Service\UuidGenerator;
use App\Service\ValidatorInterface;
use Messenger\Model\Message\Id;
use Messenger\Model\Message\MessageRepositoryInterface;
use Messenger\UseCase\Dialog\SendMessage\Command as SendCommand;
use Messenger\UseCase\Dialog\SendMessage\Handler as SendHandler;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use User\UseCase\Online\Command as OnlineCommand;
use User\UseCase\Online\Handler as OnlineHandler;

/**
 * Class SendMessage
 * @package App\Http\Handler\Messenger\Dialog
 * @Route(path="/messenger/dialog/send-message", name="messenger.dialog.send-message", methods={"POST"})
 * @OA\Post(
 *     path="/messenger/dialog/send-message",
 *     tags={"Messenger dialog - send message"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             required={"dialog_id", "content"},
 *             @OA\Property(property="dialog_id", type="string"),
 *             @OA\Property(property="content", type="string")
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
final class SendMessage
{
    private SendHandler $handler;
    private OnlineHandler $onlineHandler;
    private UuidGenerator $uuid;
    private MessageRepositoryInterface $messages;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(
        SendHandler $handler,
        OnlineHandler $onlineHandler,
        UuidGenerator $uuid,
        MessageRepositoryInterface $messages,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        ResponseFactory $response,
        Security $security
    ) {
        $this->handler = $handler;
        $this->onlineHandler = $onlineHandler;
        $this->uuid = $uuid;
        $this->messages = $messages;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->response = $response;
        $this->security = $security;
    }

    public function __invoke(Request $request): mixed
    {
        $content = (string) $request->getContent();
        $body = (array) json_decode($content, true);

        $dialogId = (string) ($body['dialog_id'] ?? '');
        $content = (string) ($body['content'] ?? '');

        $id = $this->uuid->uuid4();

        /** @var UserIdentity $user */
        $user = $this->security->getUser();
        $command = new SendCommand($id, $user->getId(), $dialogId, $content);

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

        $message = $this->messages->getById(new Id($id));

        return $this->response->json([
            'uuid' => $message->getId()->getValue(),
            'isMine' => $message->getAuthor()->getUuid()->getValue() === $user->getId(),
            'wroteAt' => ((array) $message->getWroteAt())['date'],
            'content' => $message->getContent()->getValue(),
            'isEdited' => $message->getEditStatus()->isEdited()
        ], 201);
    }
}
