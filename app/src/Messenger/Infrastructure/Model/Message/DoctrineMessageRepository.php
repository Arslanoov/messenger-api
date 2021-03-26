<?php

declare(strict_types=1);

namespace Messenger\Infrastructure\Model\Message;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Messenger\Exception\MessageNotFound;
use Messenger\Model\Message\Id;
use Messenger\Model\Message\Message;
use Messenger\Model\Message\MessageRepositoryInterface;

final class DoctrineMessageRepository implements MessageRepositoryInterface
{
    private EntityManagerInterface $entityManger;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManger)
    {
        $this->entityManger = $entityManger;
        $this->repository = $this->entityManger->getRepository(Message::class);
    }

    public function getById(Id $id): Message
    {
        if (!$message = $this->findById($id)) {
            throw new MessageNotFound();
        }

        return $message;
    }

    public function findById(Id $id): ?Message
    {
        /** @var Message | null $message */
        $message =  $this->repository->findBy([
            'uuid' => $id->getValue()
        ]);
        return $message;
    }
}
