<?php

declare(strict_types=1);

namespace App\Http\Handler\Messenger\Dialog;

use App\Http\Response\ResponseFactory;
use App\Security\UserIdentity;
use DateInterval;
use DateTimeImmutable;
use Exception\IncorrectPage;
use Messenger\ReadModel\DialogFetcherInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use User\Model\Id;

/**
 * Class Dialogs
 * @package App\Http\Handler\Messenger\Dialog
 * @Route(path="/messenger/dialogs", name="messenger.dialogs", methods={"GET"})
 * @OA\Get(
 *     path="/messenger/dialogs",
 *     tags={"Messenger dialogs list"},
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
final class Dialogs
{
    public const PER_PAGE = 20;

    private DialogFetcherInterface $dialogs;
    private ResponseFactory $response;
    private Security $security;

    public function __construct(DialogFetcherInterface $dialogs, ResponseFactory $response, Security $security)
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
        // TODO: Add author information output (joins)

        /** @var UserIdentity $user */
        $user = $this->security->getUser();

        $page = (int) ($request->get('page') ?? 1);
        if ($page <= 0) {
            throw new IncorrectPage();
        }

        return $this->response->json([
            'items' => $this->dialog($user->getId(), $page),
            'perPage' => self::PER_PAGE
        ]);
    }

    private function dialog(string $userId, int $page): array
    {
        return array_map(function (array $dialog) use ($userId) {
            $latestMessage = $this->dialogs->getLatestMessage((string) $dialog['uuid']);
            /* TODO: Extract */
            if (mb_strlen((string) $latestMessage['content']) > 25) {
                $latestMessage['content'] = mb_substr((string) $latestMessage['content'], 0, 25) . '...';
            };

            // TODO: Docs
            $response = [
                'uuid' => $dialog['uuid'],
                'partner' => [
                    'uuid' => $dialog['partner_user_uuid'],
                    'username' => $dialog['partner_user_username'],
                    'avatarUrl' => $dialog['partner_user_avatar_url'],
                    'aboutMe' => $dialog['partner_user_about_me'],
                    'isOnline' => new DateTimeImmutable((string) $dialog['partner_latest_activity']) >
                        (new DateTimeImmutable())->add(new DateInterval("PT15M")),
                ],
                'messagesCount' => $dialog['messages_count'],
                'latestMessage' => $latestMessage
            ];

            if ($latestMessage) {
                if ($userId === $latestMessage['author_id']) {
                    $response['sentByMe'] = [
                        'isSent' => true,
                        'isRead' => $latestMessage['read_status']
                    ];
                } else {
                    $response['sentByPartner'] = [
                        'isRead' => $latestMessage['read_status']
                    ];
                }
            }

            return $response;
        }, $this->dialogs->getDialogs($userId, $page));
    }
}
