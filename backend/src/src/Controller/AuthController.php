<?php

namespace App\Controller;

use App\Component\Auth\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AbstractController
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    //api/auth - я бы вынес в #[Rest\Route( выше контроллера
    //#[Rest\Post('/api/auth/login', name: '_login')]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $token = $this->authService->authenticate(
            $data['login'] ?? '',
            $data['password'] ?? ''
        );

        if (!$token) {
            return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'token' => $token,
            'type' => 'JWT',
            'expires_in' => 3600
        ]);
    }
}
