<?php

namespace App\Application\Task\Infrastructure;

use App\Application\Task\Domain\Entity\Task;
use App\Application\Task\Domain\TaskRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

        if (!$task) {
            throw new DomainException($msg ?? "Task with id $id not found");
        }

        return $task;
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria, ?array $orderBy = null): ?Task
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    public function getOneBy(array $criteria, ?array $orderBy = null, ?string $msg = null): Task
    {
        $task = $this->findOneBy($criteria, $orderBy);

        if (!$task) {
            $criteriaString = json_encode($criteria);

            throw new DomainException($msg ?? "Task with criteria $criteriaString not found");
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
