<?php

declare(strict_types=1);

namespace App\Http\Handler\Messenger\Message;

use App\Http\Response\ResponseFactory;
use App\Security\UserIdentity;
use App\Service\ValidatorInterface;
use Messenger\UseCase\Message\Read\Command as ReadCommand;
use Messenger\UseCase\Message\Read\Handler as ReadHandler;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use User\UseCase\Online\Command as OnlineCommand;
use User\UseCase\Online\Handler as OnlineHandler;

/**
 * Class Read
 * @package App\Http\Handler\Messenger\Message
 * @Route(path="/messenger/message/read", name="messenger.message.read", methods={"PATCH"})
 * @OA\Patch(
 *     path="/messenger/message/read",
 *     tags={"Messenger message read"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             required={"dialog_id", "message_id"},
 *             @OA\Property(property="dialog_id", type="string"),
 *             @OA\Property(property="message_id", type="string")
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
final class Read
{
    private ReadHandler $handler;
    private OnlineHandler $onlineHandler;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(
        ReadHandler $handler,
        OnlineHandler $onlineHandler,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        ResponseFactory $response,
        Security $security
    ) {
        $this->handler = $handler;
        $this->onlineHandler = $onlineHandler;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->response = $response;
        $this->security = $security;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request): mixed
    {
        $content = (string) $request->getContent();
        $body = (array) json_decode($content, true);

        $dialogId = (string) ($body['dialog_id'] ?? '');
        $messageId = (string) ($body['message_id'] ?? '');

        /** @var UserIdentity $user */
        $user = $this->security->getUser();
        $command = new ReadCommand($user->getId(), $dialogId, $messageId);

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

        return $this->response->json([], 204);
    }
}
