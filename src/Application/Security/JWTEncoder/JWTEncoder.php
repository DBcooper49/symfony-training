<?php

declare(strict_types=1);

namespace Application\Security\JWTEncoder;

use Application\Security\User;

interface JWTEncoder
{
    public function encode(User $user): string;

    public function decode(string $token): User;
}
