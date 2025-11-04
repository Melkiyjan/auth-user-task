<?php
/**
 * UnSpot by UmbrellaIT
 */


namespace App\Component\Security;

class SecurityContext
{
    private ?object $userData = null;

    public function setUserData(object $userData): void
    {
        $this->userData = $userData;
    }

    public function getUser(): ?object
    {
        return $this->userData;
    }

    public function getUserEmail(): ?string
    {
        return $this->userData->email ?? null;
    }

    public function getUserId(): ?int
    {
        return $this->userData->user_id ?? null;
    }

    public function getRoles(): array
    {
        return $this->userData->roles ?? [];
    }

    public function isGranted(array $roles): bool
    {
        if (empty($this->userData)) {
            return false;
        }

        return !empty(array_intersect($roles, $this->userData->roles));
    }
}
