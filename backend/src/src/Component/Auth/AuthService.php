<?php

namespace App\Component\Auth;

use App\Application\User\Domain\UserRepositoryInterface;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService
{
    private string $algorithm = 'HS256';

    public function __construct(
        private readonly string $secretKey,
        private readonly UserRepositoryInterface $userRepository
    ){
    }

    public function authenticate(string $login, string $password): ?string
    {
        $user = $this->userRepository->findOneBy([UserRepositoryInterface::FILTER_EMAIL => $login]);

        if (!$user || !password_verify($password, $user->getPassword())) {
            return null;
        }

        $payload = [
            'iat' => time(),
            'exp' => time() + 3600,
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'user_id' => (string) $user->getId()
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function validateToken(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->secretKey, $this->algorithm));
        } catch (Exception $exception) {
            return null;
        }
    }
}
