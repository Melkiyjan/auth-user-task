<?php

declare(strict_types=1);

namespace App\Application\Task\Infrastructure;

use DomainException;
use Override;
use App\Application\Task\Domain\Entity\Task;
use App\Application\Task\Domain\TaskRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findById(string $id): ?Task
    {
        return $this->find($id);
    }

    public function getById(string $id, ?string $msg = null): Task
    {
        $task = $this->find($id);

        if (!$task instanceof Task) {
            throw new DomainException($msg ?? sprintf('Task with id %s not found', $id));
        }

        return $task;
    }

    /**
     * @param string[] $criteria
     * @param string[]|null $orderBy
     */
    #[Override]
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param string[] $criteria
     * @param string[]|null $orderBy
     */
    #[Override]
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Task
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * @param string[] $criteria
     * @param string[]|null $orderBy
     */
    public function getOneBy(array $criteria, ?array $orderBy = null, ?string $msg = null): Task
    {
        $task = $this->findOneBy($criteria, $orderBy);

        if (!$task instanceof Task) {
            $criteriaString = json_encode($criteria, JSON_THROW_ON_ERROR);

            throw new DomainException($msg ?? sprintf('Task with criteria %s not found', $criteriaString));
        }

        return $task;
    }

    public function save(Task $task): void
    {
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
    }

    public function remove(Task $task): void
    {
        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();
    }
}
