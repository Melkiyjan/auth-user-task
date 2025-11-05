<?php

declare(strict_types=1);

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
     * @param string[]      $criteria
     * @param string[]|null $orderBy
     *
     * @return Task[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * @param string[]      $criteria
     * @param string[]|null $orderBy
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Task;

    /**
     * @param string[]      $criteria
     * @param string[]|null $orderBy
     *
     * @throws JsonException
     */
    public function getOneBy(array $criteria, ?array $orderBy = null, ?string $msg = null): Task;

    public function save(Task $task): void;

    public function remove(Task $task): void;
}
