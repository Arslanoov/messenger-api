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
use Exception\IncorrectPage;
use Messenger\Model\Dialog\DialogRepositoryInterface;
use Messenger\Model\Dialog\Id as DialogId;
use Messenger\ReadModel\MessageFetcherInterface;
use Messenger\UseCase\Dialog\Read\Command as ReadCommand;
use Messenger\UseCase\Dialog\Read\Handler as ReadHandler;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use User\UseCase\Online\Command as OnlineCommand;
use User\UseCase\Online\Handler as OnlineHandler;

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
    public const PER_PAGE = 25;

    private DialogRepositoryInterface $dialogs;
    private AuthorRepositoryInterface $authors;
    private MessageFetcherInterface $messages;
    private ReadHandler $handler;
    private OnlineHandler $onlineHandler;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(
        DialogRepositoryInterface $dialogs,
        AuthorRepositoryInterface $authors,
        MessageFetcherInterface $messages,
        ReadHandler $handler,
        ResponseFactory $response,
        Security $security
    ) {
        $this->dialogs = $dialogs;
        $this->authors = $authors;
        $this->messages = $messages;
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
        /** @var UserIdentity $user */
        $user = $this->security->getUser();

        $page = (int) ($request->get('page') ?? 1);
        if ($page < 1) {
            throw new IncorrectPage();
        }

        $author = $this->authors->getById(new AuthorId($user->getId()));
        $dialog = $this->dialogs->getById(new DialogId($id));
        if (!$dialog->hasMember($author)) {
            throw new DialogNotFound();
        }

        // TODO: Test
        $this->handler->handle(new ReadCommand($user->getId(), $dialog->getUuid()->getValue()));
        $this->onlineHandler->handle(new OnlineCommand($user->getUsername()));

        $messages = $this->messages->getMessages($dialog->getUuid()->getValue(), $page);

        return $this->response->json([
            'items' => array_map(function (array $message) use ($author) {
                return [
                    'uuid' => $message['uuid'],
                    'isMine' => $message['author_id'] === $author->getUuid()->getValue(),
                    'wroteAt' => $message['wrote_at'],
                    'content' => $message['content']
                ];
            }, $messages)
        ]);
    }
}
