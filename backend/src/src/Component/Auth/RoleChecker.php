<?php

namespace App\Component\Auth;

class RoleChecker
{
    public function isGranted(array $requiredRoles, array $userRoles): bool
    {
        return !empty(array_intersect($requiredRoles, $userRoles));
    }
}
