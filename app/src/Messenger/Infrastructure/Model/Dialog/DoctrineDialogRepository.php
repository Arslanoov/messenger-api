<?php

declare(strict_types=1);

namespace Messenger\Infrastructure\Model\Dialog;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Messenger\Exception\DialogNotFound;
use Messenger\Model\Author\Author;
use Messenger\Model\Dialog\Dialog;
use Messenger\Model\Dialog\DialogRepositoryInterface;
use Messenger\Model\Dialog\Id;

final class DoctrineDialogRepository implements DialogRepositoryInterface
{
    private EntityManagerInterface $entityManger;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManger)
    {
        $this->entityManger = $entityManger;
        /** @var EntityRepository $repository */
        $repository = $this->entityManger->getRepository(Dialog::class);
        $this->repository = $repository;
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
                ->select('COUNT(d.uuid)')
                ->where('d.firstAuthor = :firstAuthor AND d.secondAuthor = :secondAuthor')
                ->orWhere('d.firstAuthor = :secondAuthor AND d.secondAuthor = :firstAuthor')
                ->setParameter(':firstAuthor', $firstMember->getUuid()->getValue())
                ->setParameter(':secondAuthor', $secondMember->getUuid()->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }
}
