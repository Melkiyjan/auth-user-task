<?php

namespace App\EventListener;

use App\Attribute\IsGranted as IsGrantedAttribute;
use App\Component\Security\SecurityContext;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class IsGrantedEventListener
{
    public function __construct(private readonly SecurityContext $securityContext)
    {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            [$controllerObject, $methodName] = $controller;
        } else {
            return;
        }

        $controllerReflection = new ReflectionClass($controllerObject);
        $methodReflection = $controllerReflection->getMethod($methodName);

        $classAttributes = $controllerReflection->getAttributes(IsGrantedAttribute::class);
        $methodAttributes = $methodReflection->getAttributes(IsGrantedAttribute::class);

        if (empty($classAttributes) && empty($methodAttributes)) {
            return;
        }

        $attributes = array_merge($classAttributes, $methodAttributes);

        foreach ($attributes as $attribute) {
            /** @var IsGrantedAttribute $isGranted */
            $isGranted = $attribute->newInstance();

            if (!$this->securityContext->getUser()) {
                $this->setErrorResponse($event, 'Unauthorized', Response::HTTP_UNAUTHORIZED);

                return;
            }

            // Проверяем роли
            if (!empty($isGranted->roles) && !$this->securityContext->isGranted($isGranted->roles)) {
                $this->setErrorResponse($event, 'Access denied', Response::HTTP_FORBIDDEN);

                return;
            }

            if ($isGranted->permission && !$this->checkPermission($isGranted->permission)) {
                $this->setErrorResponse($event, 'Permission denied', Response::HTTP_FORBIDDEN);

                return;
            }
        }
    }

    private function setErrorResponse(ControllerEvent $event, string $message, int $code): void
    {
        $response = new JsonResponse(['error' => $message], $code);

        $event->setController(function() use ($response) {
            return $response;
        });
    }

    private function checkPermission(string $permission): bool
    {
        // Заглушка для будущей реализации
        return true;
    }
}
