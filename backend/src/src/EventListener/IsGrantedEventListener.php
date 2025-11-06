<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Attribute\IsGranted as IsGrantedAttribute;
use App\Component\Security\SecurityContext;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

#[AsEventListener]
class IsGrantedEventListener
{
    public function __construct(private readonly SecurityContext $securityContext)
    {
    }

    public function onKernelController(ControllerEvent $controllerEvent): void
    {
        $controller = $controllerEvent->getController();
        if (!is_array($controller)) {
            return;
        }

        $reflectionMethod = new ReflectionMethod($controller[0], $controller[1]);
        $attributes = array_merge(
            $reflectionMethod->getAttributes(IsGrantedAttribute::class),
            (new ReflectionClass($controller[0]))->getAttributes(IsGrantedAttribute::class)
        );

        if ($attributes === []) {
            return;
        }

        foreach ($attributes as $attribute) {
            /** @var IsGrantedAttribute $meta */
            $meta = $attribute->newInstance();
            $required = $meta->roles ?? [];
            if (!$this->securityContext->isGranted($required)) {
                $controllerEvent->setController(fn(): JsonResponse => new JsonResponse(
                    ['error' => 'Forbidden'],
                    Response::HTTP_FORBIDDEN
                ));

                return;
            }
        }
    }
}
