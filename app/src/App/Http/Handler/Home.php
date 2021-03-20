<?php

declare(strict_types=1);

namespace App\Http\Handler;

use App\Http\Response\ResponseFactory;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Home
 * @package App\Http\Handler
 * @Route(path="/", name="home", methods={"GET"})
 * @OA\Info(
 *     version="1.0.0",
 *     title="Messenger API",
 *     description="HTTP JSON API",
 * ),
 * @OA\Server(
 *     url="/"
 * ),
 * @OA\SecurityScheme(
 *     type="oauth2",
 *     securityScheme="oauth2",
 *     @OA\Flow(
 *         flow="implicit",
 *         authorizationUrl="/token",
 *         scopes={
 *             "common": "Common"
 *         }
 *     )
 * ),
 * @OA\Schema(
 *     schema="ErrorModel",
 *     type="object",
 *     @OA\Property(property="error", type="object", nullable=true,
 *         @OA\Property(property="code", type="integer"),
 *         @OA\Property(property="message", type="string"),
 *     ),
 *     @OA\Property(property="violations", type="array", nullable=true, @OA\Items(
 *         type="object",
 *         @OA\Property(property="propertyPath", type="string"),
 *         @OA\Property(property="title", type="string")
 *     ))
 * ),
 * @OA\Get(
 *     path="/",
 *     tags={"API"},
 *     description="API Home",
 *     @OA\Response(
 *         response="200",
 *         description="Success response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="version", type="string"),
 *             @OA\Property(property="name", type="string")
 *         )
 *     )
 * )
 */
final class Home
{
    private ResponseFactory $response;

    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    public function __invoke(): mixed
    {
        return $this->response->json([
            'v' => '1.0',
            'name' => 'messenger'
        ]);
    }
}
