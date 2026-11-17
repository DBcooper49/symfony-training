<?php

namespace Application\Command;

use Application\Security\User;
use Domain\Collection\UsersCollection;
use Infrastructure\LexikJWTEncoder\LexikJWTEncoder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:jwt:encode')]
final class JWTCommand extends Command
{
    public function __construct(
        private readonly LexikJWTEncoder $JWTEncoder,
        private readonly UsersCollection $users
    )
    {
        parent::__construct();
    }

    public function configure(){
    }

    public function execute(InputInterface $input, OutputInterface $output):int
    {
        $admin = $this->users->findOneByEmail('admin@mail.com');
        $jwt = $this->JWTEncoder->encode(new User($admin));

        dump($this->JWTEncoder->decode($jwt));
        return Command::SUCCESS;
    }
}
