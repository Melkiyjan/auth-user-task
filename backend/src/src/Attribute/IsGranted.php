<?php

declare(strict_types=1);

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class IsGranted
{
    /**
     * @param string[] $roles
     */
    public function __construct(
        public array $roles = [],
        public ?string $permission = null
    ) {
    }
}
