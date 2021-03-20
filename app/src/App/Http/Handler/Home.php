<?php

declare(strict_types=1);

namespace App\Http\Handler;

use App\Http\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Home
 * @package App\Http\Handler
 * @Route(path="/", name="home", methods={"GET"})
 */
final class Home
{
    private ResponseFactory $response;

    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    public function __invoke()
    {
        return $this->response->json([
            'v' => '1.0',
            'name' => 'messenger'
        ]);
    }
}
