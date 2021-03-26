<?php

declare(strict_types=1);

namespace App\Http\Handler\Messenger\Message;

use App\Http\Response\ResponseFactory;
use App\Service\ValidatorInterface;
use Messenger\UseCase\Message\Edit\Command;
use Messenger\UseCase\Message\Edit\Handler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Trikoder\Bundle\OAuth2Bundle\Security\Authentication\Token\OAuth2Token;

/**
 * Class Edit
 * @package App\Http\Handler\Messenger\Message
 * @Route(path="/messenger/message/edit", name="messenger.message.edit", methods={"PATCH"})
 * @OA\Post(
 *     path="/messenger/message/edit",
 *     tags={"Messenger message edit"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             required={"message_id", "content"},
 *             @OA\Property(property="message_id", type="string"),
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
final class Edit
{
    private Handler $handler;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;
    private ResponseFactory $response;
    private OAuth2Token $tokenizer;

    public function __construct(
        Handler $handler,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        ResponseFactory $response,
        OAuth2Token $tokenizer // TODO: Separate service
    ) {
        $this->handler = $handler;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->response = $response;
        $this->tokenizer = $tokenizer;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request): mixed
    {
        $content = (string) $request->getContent();
        $body = (array) json_decode($content, true);

        $messageId = (string) $body['message_id'];
        $messageContent = (string) $body['content'];

        // TODO: Check for correctness
        $userId = (string) $this->tokenizer->getAttribute('oauth_client_id');

        $command = new Command($userId, $messageId, $messageContent);

        try {
            $this->validator->validateObjects([$command]);
        } catch (ValidationFailedException $e) {
            $data = $this->serializer->serialize($e->getViolations(), 'json');
            /** @var array<string, string | int | array> $decoded */
            $decoded = json_decode($data, true);
            return $this->response->json($decoded, 422);
        }

        $this->handler->handle($command);

        return $this->response->json([], 204);
    }
}
