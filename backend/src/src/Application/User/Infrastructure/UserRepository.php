<?php

namespace Infrastructure;

use App\Application\User\Domain\Entity\User;
use App\Application\User\Domain\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use DomainException;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findById(string $id): ?User
    {
        return $this->find($id);
    }

    public function getById(string $id, ?string $msg = null): User
    {
        $user = $this->find($id);

        if (!$user) {
            throw new DomainException($msg ?? "User with id $id not found");
        }

        return $user;
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria, ?array $orderBy = null): ?User
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    public function getOneBy(array $criteria, ?array $orderBy = null, ?string $msg = null): User
    {
        $user = $this->findOneBy($criteria, $orderBy);

        if (!$user) {
            $criteriaString = json_encode($criteria);

            throw new DomainException($msg ?? "User with criteria $criteriaString not found");
        }

        return $user;
    }

    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function remove(User $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }
}
