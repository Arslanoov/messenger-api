<?php

declare(strict_types=1);

namespace App\Http\Handler\Messenger\Dialog;

use App\Http\Response\ResponseFactory;
use App\Security\UserIdentity;
use Assert\AssertionFailedException;
use Domain\Exception\DomainAssertionException;
use Messenger\Exception\DialogNotFound;
use Messenger\Model\Author\AuthorRepositoryInterface;
use Messenger\Model\Author\Id as AuthorId;
use Messenger\Model\Dialog\Dialog as DialogModel;
use Exception\IncorrectPage;
use Messenger\Model\Dialog\DialogRepositoryInterface;
use Messenger\Model\Dialog\Id as DialogId;
use Messenger\Model\Message\Message;
use Messenger\UseCase\Dialog\Read\Command;
use Messenger\UseCase\Dialog\Read\Handler;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class Dialog
 * @package App\Http\Handler\Messenger\Messages
 * @Route(path="/messenger/dialog/{id}/messages", name="messenger.dialog.messages", methods={"GET"})
 * @OA\Get(
 *     path="/messenger/dialog/{id}/messages",
 *     tags={"Messenger dialog messages"},
 *     @OA\Parameter(
 *         name="page",
 *         in="path",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
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
final class Messages
{
    public const PAGE_SIZE = 25;

    private DialogRepositoryInterface $dialogs;
    private AuthorRepositoryInterface $authors;
    private Handler $handler;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(
        DialogRepositoryInterface $dialogs,
        AuthorRepositoryInterface $authors,
        Handler $handler,
        ResponseFactory $response,
        Security $security
    ) {
        $this->dialogs = $dialogs;
        $this->authors = $authors;
        $this->handler = $handler;
        $this->response = $response;
        $this->security = $security;
    }

    /**
     * @param Request $request
     * @param string $id
     * @return mixed
     * @throws IncorrectPage
     * @throws AssertionFailedException
     * @throws DomainAssertionException
     * @throws IncorrectPage
     */
    public function __invoke(string $id, Request $request): mixed
    {
        // TODO: Add author information output
        // TODO: Change repository to raw pdo
        /** @var UserIdentity $user */
        $user = $this->security->getUser();

        $page = (int) ($request->get('page') ?? 1);
        if ($page <= 0) {
            throw new IncorrectPage();
        }

        $author = $this->authors->getById(new AuthorId($user->getId()));
        $dialog = $this->dialogs->getById(new DialogId($id));
        if (!$dialog->hasMember($author)) {
            throw new DialogNotFound();
        }

        // TODO: Fix handler
        $this->handler->handle(new Command($user->getId(), $dialog->getUuid()->getValue()));

        return $this->response->json([
            'messages' => array_map(function (Message $message) use ($user) {
                return [
                    'uuid' => $message->getId()->getValue(),
                    'isMine' => $message->getAuthor()->getUuid() === $user->getId(),
                    'wroteAt' => $message->getWroteAt()->format('d.m.Y H:i:s'),
                    'content' => $message->getContent()->getValue()
                ];
            }, $dialog->getMessages()->slice(($page - 1) * self::PAGE_SIZE, self::PAGE_SIZE))
        ]);
    }
}
