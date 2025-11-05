<?php

declare(strict_types=1);

namespace App\Application\User\Infrastructure;

use JsonException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Override;
use App\Application\User\Domain\Entity\User;
use App\Application\User\Domain\UserRepositoryInterface;
use DomainException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
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

        if ($user === null) {
            throw new DomainException($msg ?? sprintf('User with id %s not found', $id));
        }

        return $user;
    }

    #[Override]
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    #[Override]
    public function findOneBy(array $criteria, ?array $orderBy = null): ?User
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * @param string[]      $criteria
     * @param string[]|null $orderBy
     *
     * @throws JsonException
     */
    public function getOneBy(array $criteria, ?array $orderBy = null, ?string $msg = null): User
    {
        $user = $this->findOneBy($criteria, $orderBy);

        if (!$user instanceof User) {
            $criteriaString = json_encode($criteria, JSON_THROW_ON_ERROR);

            throw new DomainException($msg ?? sprintf('User with criteria %s not found', $criteriaString));
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
