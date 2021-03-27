<?php

declare(strict_types=1);

namespace Messenger\Infrastructure\Model\Author;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Messenger\Exception\AuthorNotFound;
use Messenger\Model\Author\Author;
use Messenger\Model\Author\AuthorRepositoryInterface;
use Messenger\Model\Author\Id;

final class DoctrineAuthorRepository implements AuthorRepositoryInterface
{
    private EntityManagerInterface $entityManger;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManger)
    {
        $this->entityManger = $entityManger;
        $this->repository = $this->entityManger->getRepository(Author::class);
    }

    public function getById(Id $id): Author
    {
        if (!$author = $this->findById($id)) {
            throw new AuthorNotFound();
        }

        return $author;
    }

    public function findById(Id $id): ?Author
    {
        /** @var Author | null $author */
        $author = $this->repository->findOneBy([
            'uuid' => $id->getValue()
        ]);

        return $author;
    }
}
