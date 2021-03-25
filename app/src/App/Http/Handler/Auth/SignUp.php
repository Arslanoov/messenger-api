<?php

declare(strict_types=1);

namespace App\Http\Handler\Auth;

use App\Exception\Service\TransactionFailed;
use App\Http\Response\ResponseFactory;
use App\Service\TransactionInterface;
use App\Service\UuidGenerator;
use App\Service\ValidatorInterface;
use Messenger\UseCase\Author\CreateFromUser\Command as CreateAuthorCommand;
use Messenger\UseCase\Author\CreateFromUser\Handler as CreateAuthorHandler;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use User\UseCase\SignUp\Request\Command as SignUpRequestCommand;
use User\UseCase\SignUp\Request\Handler as SignUpRequestHandler;

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
    private SignUpRequestHandler $signUpHandler;
    private CreateAuthorHandler $createAuthorHandler;
    private TransactionInterface $transaction;
    private UuidGenerator $generator;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;
    private ResponseFactory $response;

    public function __construct(
        SignUpRequestHandler $signUpHandler,
        CreateAuthorHandler $createAuthorHandler,
        TransactionInterface $transaction,
        UuidGenerator $generator,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        ResponseFactory $response
    ) {
        $this->signUpHandler = $signUpHandler;
        $this->createAuthorHandler = $createAuthorHandler;
        $this->transaction = $transaction;
        $this->generator = $generator;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->response = $response;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws TransactionFailed
     */
    public function __invoke(Request $request): mixed
    {
        $content = (string) $request->getContent();
        $body = (array) json_decode($content, true);

        $username = (string) $body['username'];
        $password = (string) $body['password'];

        $id = $this->generator->uuid4();

        $signUpCommand = new SignUpRequestCommand($id, $username, $password);
        $createAuthorCommand = new CreateAuthorCommand($id);

        try {
            $this->validator->validateObjects([$signUpCommand, $createAuthorCommand]);
        } catch (ValidationFailedException $e) {
            $data = $this->serializer->serialize($e->getViolations(), 'json');
            /** @var array<string, string | int | array> $decoded */
            $decoded = json_decode($data, true);
            return $this->response->json($decoded, 422);
        }

        $this->transaction->begin();

        try {
            $this->signUpHandler->handle($signUpCommand);
            $this->createAuthorHandler->handle($createAuthorCommand);
            $this->transaction->commit();
        } catch (TransactionFailed $e) {
            $this->transaction->rollback();
            throw $e;
        }

        return $this->response->json([
            'username' => $username
        ], 201);
    }
}
