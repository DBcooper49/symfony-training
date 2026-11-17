<?php

namespace Infrastructure\LexikJWTEncoder;
use Application\Security\JWTEncoder\JWTEncoder;
use Application\Security\User;
use Domain\Collection\UsersCollection;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final readonly class LexikJWTEncoder implements JWTEncoder
{
    public function __construct(
        private \Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface $JWTTokenManager,
        private UsersCollection $users
    ) {
    }
    public function encode(User $user): string{
        return $this->JWTTokenManager->create($user);
        //throw new \Exception('Not implemented');
    }

    public function decode(string $jwt): User {
        $data = $this->JWTTokenManager->parse($jwt);
        if(!array_key_exists('username', $data)){
            throw new \RuntimeException('Invalid jwt');
        }
        $user = $this->users->findOneByEmail($data['username']);
        if(null === $user){
            throw new UserNotFoundException('user not found');
        }
        return new User($user);
        //throw new \Exception('Not implemented');
    }
}
