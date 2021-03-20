<?php

declare(strict_types=1);

namespace App\Http\Handler\Auth;

use App\Http\Response\ResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use User\UseCase\SignUp\Request\Command;
use User\UseCase\SignUp\Request\Handler;

/**
 * Class SignUp
 * @package App\Http\Handler\Auth
 * @Route(path="/auth/sign-up", name="auth.sign-up", methods={"POST"})
 */
final class SignUp
{
    private Handler $handler;
    private ResponseFactory $response;

    public function __construct(Handler $handler, ResponseFactory $response)
    {
        $this->handler = $handler;
        $this->response = $response;
    }

    public function __invoke(Request $request): mixed
    {
        $content = (string) $request->getContent();
        $body = (array) json_decode($content, true);

        $username = (string) $body['username'];
        $password = (string) $body['password'];

        $this->handler->handle(new Command($username, $password));

        return $this->response->json([
            'username' => $username
        ]);
    }
}
