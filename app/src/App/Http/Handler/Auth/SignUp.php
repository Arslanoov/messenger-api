<?php

declare(strict_types=1);

namespace App\Http\Handler\Auth;

use App\Http\Response\ResponseFactory;
use Domain\Exception\DomainException;
use OpenApi\Annotations as OA;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use User\UseCase\SignUp\Request\Command;
use User\UseCase\SignUp\Request\Handler;

/**
 * Class SignUp
 * @package App\Http\Handler\Auth
 * @Route(path="/auth/sign-up", name="auth.sign-up", methods={"POST"})
 * @OA\Post(
 *     path="/auth/sign-up",
 *     tags={"Sign Up Request"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             required={"username", "password"},
 *             @OA\Property(property="username", type="string"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Success response",
 *             @OA\JsonContent(
 *                 type="object",
 *                 @OA\Property(property="username", type="string", nullable=false)
 *             )
 *          )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Errors",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", nullable=true)
 *         )
 *     )
 *   )
 * )
 */
final class SignUp
{
    private Handler $handler;
    private LoggerInterface $logger;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;
    private ResponseFactory $response;

    public function __construct(
        Handler $handler,
        LoggerInterface $logger,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        ResponseFactory $response
    ) {
        $this->handler = $handler;
        $this->logger = $logger;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->response = $response;
    }

    public function __invoke(Request $request): mixed
    {
        $content = (string) $request->getContent();
        $body = (array) json_decode($content, true);

        $username = (string) $body['username'];
        $password = (string) $body['password'];

        $signUpCommand = new Command($username, $password);

        $violations = $this->validator->validate($signUpCommand);
        if (count($violations)) {
            $data = $this->serializer->serialize($violations, 'json');
            /** @var array<string, string | int | array> $decoded */
            $decoded = json_decode($data, true);
            return $this->response->json($decoded, 422);
        }

        try {
            $this->handler->handle($signUpCommand);
        } catch (DomainException $e) {
            $this->logger->debug($e->getMessage(), ['exception' => $e]);
            return $this->response->json([
                'message' => $e->getMessage()
            ], (int) $e->getCode());
        }

        return $this->response->json([
            'username' => $username
        ], 201);
    }
}
