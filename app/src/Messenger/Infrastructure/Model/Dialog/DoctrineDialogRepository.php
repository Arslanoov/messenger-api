<?php

declare(strict_types=1);

namespace Messenger\Infrastructure\Model\Dialog;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Messenger\Exception\DialogNotFound;
use Messenger\Model\Author\Author;
use Messenger\Model\Dialog\Dialog;
use Messenger\Model\Dialog\DialogRepositoryInterface;
use Messenger\Model\Dialog\Id;

final class DoctrineDialogRepository implements DialogRepositoryInterface
{
    private EntityManagerInterface $entityManger;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManger)
    {
        $this->entityManger = $entityManger;
        $this->repository = $this->entityManger->getRepository(Dialog::class);
    }

    public function getById(Id $id): Dialog
    {
        if (!$dialog = $this->findById($id)) {
            throw new DialogNotFound();
        }

        return $dialog;
    }

    public function findById(Id $id): ?Dialog
    {
        /** @var Dialog | null $dialog */
        $dialog = $this->repository->findOneBy([
            'uuid' => $id->getValue()
        ]);

        return $dialog;
    }

    public function hasDialogByMembers(Author $firstMember, Author $secondMember): bool
    {
        return $this->repository->createQueryBuilder('d')
                ->select('COUNT(d.id)')
                ->where('d.first_author_id = :first_author_id AND d.second_author_id = :second_author_id')
                ->orWhere('d.first_author_id = :second_author_id AND d.second_author_id = :first_author_id')
                ->setParameter(':first_author_id', $firstMember->getUuid()->getValue())
                ->setParameter(':second_author_id', $secondMember->getUuid()->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }
}
