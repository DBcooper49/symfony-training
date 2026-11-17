<?php

namespace Application\GraphQL;

use Application\Security\User;
use Domain\Collection\UsersCollection;
use Infrastructure\LexikJWTEncoder\LexikJWTEncoder;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class AuthResolver
{
    public function __construct(
        private readonly LexikJWTEncoder $JWTEncoder,
        private readonly UsersCollection $users,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    )
    {

    }

    public function login(array | \ArrayObject $input)
    {
        $user = $this->users->findOneByEmail($input['email']);

        if(!$user){
            throw new \RuntimeException('User not found');
        }

        if(!$this->userPasswordHasher->isPasswordValid(new User($user), $input['password'])){
            throw new \RuntimeException('Wrong password');
        }

        return $this->JWTEncoder->encode(new User($user));
    }
}
