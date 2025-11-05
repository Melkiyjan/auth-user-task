<?php

declare(strict_types=1);

namespace App\Application\User\Domain;

use App\Application\User\Domain\Entity\User;
use Symfony\Component\HttpFoundation\Exception\JsonException;

interface UserRepositoryInterface
{
    public const string FILTER_EMAIL = 'email';

    public function findById(string $id): ?User;

    /**
     * @throws JsonException
     */
    public function getById(string $id, ?string $msg = null): User;

    /**
     * @param string[]      $criteria
     * @param string[]|null $orderBy
     *
     * @return User[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * @param string[]      $criteria
     * @param string[]|null $orderBy
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?User;

    /**
     * @param string[]      $criteria
     * @param string[]|null $orderBy
     *
     * @throws JsonException
     */
    public function getOneBy(array $criteria, ?array $orderBy = null, ?string $msg = null): User;

    public function save(User $user): void;

    public function remove(User $user): void;
}
