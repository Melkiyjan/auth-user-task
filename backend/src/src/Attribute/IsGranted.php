<?php

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class IsGranted
{
    public function __construct(
        public array $roles = [],
        public ?string $permission = null
    ) {
    }
}
