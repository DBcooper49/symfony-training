<?php

namespace Application\GraphQL;
use Application\MessageBus\CommandBus;
use Domain\Collection\DinosaursCollection;
use Domain\Exception\DinosaurNotFoundException;
use Domain\Model\Dinosaur;
use Domain\UseCase\CreateDinosaur;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class DinosaurResolver
{
    public function __construct(
        private DinosaursCollection $dinosaurs,
        private CommandBus $commandBus,
        private Security $security,
    )
    {
    }

    public function dinosaurs(?string $search): array
    {
        $this->security->getUser() ?? throw new \RuntimeException('User not authenticated');
        return $this->dinosaurs->search($search);
    }

    public function dinosaur(string $id): Dinosaur
    {
        return $this->dinosaurs->find($id)
            ?? throw new DinosaurNotFoundException($id);
    }

    public function createDinosaur(array | \ArrayObject $input): Dinosaur
    {
        $output = $this->commandBus->dispatch(
            new CreateDinosaur\Input(
                $input['name'],
                $input['gender'],
                $input['speciesId'],
                $input['age'],
                $input['eyesColor'],
                new \DateTimeImmutable('2020-01-01'),
            )
        );
        return $output->dinosaur;
    }
}
