<?php

declare(strict_types=1);

namespace App\Http\Handler\Messenger\Dialog;

use App\Http\Response\ResponseFactory;
use App\Security\UserIdentity;
use App\Service\ValidatorInterface;
use Messenger\UseCase\Dialog\Remove\Command as RemoveCommand;
use Messenger\UseCase\Dialog\Remove\Handler as RemoveHandler;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use User\UseCase\Online\Command as OnlineCommand;
use User\UseCase\Online\Handler as OnlineHandler;

/**
 * Class Remove
 * @package App\Http\Handler\Messenger\Dialog
 * @Route(path="/messenger/dialog/remove", name="messenger.dialog.remove", methods={"DELETE"})
 * @OA\Delete(
 *     path="/messenger/dialog/remove",
 *     tags={"Messenger dialog remove"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             required={"dialog_id"},
 *             @OA\Property(property="dialog_id", type="string")
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
final class Remove
{
    private RemoveHandler $handler;
    private OnlineHandler $onlineHandler;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(
        RemoveHandler $handler,
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

        /** @var UserIdentity $user */
        $user = $this->security->getUser();
        $command = new RemoveCommand($user->getId(), $dialogId);

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
