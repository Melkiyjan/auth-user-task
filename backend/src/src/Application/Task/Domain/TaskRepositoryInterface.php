<?php

namespace App\Application\Task\Domain;

use App\Application\Task\Domain\Entity\Task;
use Symfony\Component\HttpFoundation\Exception\JsonException;

interface TaskRepositoryInterface
{
    public function findById(string $id): ?Task;

    /**
     * @throws JsonException
     */
    public function getById(string $id, ?string $msg = null): Task;

    /**
     * @return Task[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    public function findOneBy(array $criteria, ?array $orderBy = null): ?Task;

    /**
     * @throws JsonException
     */
    public function getOneBy(array $criteria, ?array $orderBy = null, ?string $msg = null): Task;

    public function save(Task $task): void;

    public function remove(Task $task): void;
}
