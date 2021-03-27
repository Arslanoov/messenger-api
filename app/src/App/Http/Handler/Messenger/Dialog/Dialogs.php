<?php

declare(strict_types=1);

namespace App\Http\Handler\Messenger\Dialog;

use App\Http\Response\ResponseFactory;
use App\Security\UserIdentity;
use Exception\IncorrectPage;
use Messenger\ReadModel\DialogFetcherRepository;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class Dialogs
 * @package App\Http\Handler\Messenger\AuthorList
 * @Route(path="/messenger/dialogs", name="messenger.dialogs", methods={"GET"})
 * @OA\Get(
 *     path="/messenger/dialogs",
 *     tags={"Messenger dialogs list"},
 *     @OA\Response(
 *         response=200,
 *         description="Success response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="items", type="array", @OA\Items(
 *                 @OA\Property(property="uuid", type="string"),
 *                 @OA\Property(property="first_author_id", type="string"),
 *                 @OA\Property(property="second_author_id", type="string"),
 *                 @OA\Property(property="messages_count", type="integer"),
 *                 @OA\Property(property="not_read_count", type="integer")
 *             )),
 *             @OA\Property(property="perPage", type="integer")
 *         )
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
final class Dialogs
{
    public const PER_PAGE = 20;

    private DialogFetcherRepository $dialogs;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(DialogFetcherRepository $dialogs, ResponseFactory $response, Security $security)
    {
        $this->dialogs = $dialogs;
        $this->response = $response;
        $this->security = $security;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws IncorrectPage
     */
    public function __invoke(Request $request): mixed
    {
        /** @var UserIdentity $user */
        $user = $this->security->getUser();

        $page = (int) ($request->get('page') ?? 1);
        if ($page <= 0) {
            throw new IncorrectPage();
        }

        return $this->response->json([
            'items' => $this->dialogs->getDialogs($user->getId(), $page),
            'perPage' => self::PER_PAGE
        ]);
    }
}
