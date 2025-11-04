<?php

namespace App\EventListener;

use App\Attribute\IsGranted as IsGrantedAttribute;
use App\Component\Security\SecurityContext;
use ReflectionClass;
use ReflectionMethod;
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
        if (!is_array($controller)) {
            return;
        }

        $refMethod = new ReflectionMethod($controller[0], $controller[1]);
        $attributes = array_merge(
            $refMethod->getAttributes(IsGrantedAttribute::class),
            (new ReflectionClass($controller[0]))->getAttributes(IsGrantedAttribute::class)
        );

        if (!$attributes) {
            return;
        }
        foreach ($attributes as $attr) {
            /** @var IsGrantedAttribute $meta */
            $meta = $attr->newInstance();
            $required = $meta->roles ?? [];
            if (!$this->securityContext->isGranted($required)) {
                $event->setController(function () {
                    return new JsonResponse(
                        ['error' => 'Forbidden'],
                        Response::HTTP_FORBIDDEN
                    );
                });

                return;
            }
        }
    }

    private function checkPermission(string $permission): bool
    {
        // Заглушка для будущей реализации
        return true;
    }
}
