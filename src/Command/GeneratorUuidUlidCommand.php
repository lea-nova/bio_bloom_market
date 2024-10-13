<?php

namespace App\Command;

use App\Entity\Adresse;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\Ulid;

#[AsCommand(
    name: 'GeneratorUuidUlidCommand',
    description: 'Generate UUID and ULID for User and Adresse',
)]
class GeneratorUuidUlidCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Génère et attribue des UUID et ULID ( UUID pour user et ULID pour adresse')
            // ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->generateUuidForUsers($output);
        $this->generateUlidForAdresses($output);

        $this->entityManager->flush();

        $output->writeln('UUIDs et ULIDs générés avec succès.');
        return Command::SUCCESS;
    }

    private function generateUuidForUsers(OutputInterface $output)
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $user->setUuid(Uuid::v4());
            $output->writeln(sprintf('UUID généré pour l\'utilisateur ID : %s', $user->getId()));
            $this->entityManager->persist($user);
        }
    }

    private function generateUlidForAdresses(OutputInterface $output)
    {
        $adresses = $this->entityManager->getRepository(Adresse::class)->findAll();
        foreach ($adresses as $adresse) {
            $adresse->setUlid(new Ulid());
            $output->writeln(sprintf('ULID généré pour l\'adresse ID : %s', $adresse->getId()));
            $this->entityManager->persist($adresse);
        }
    }
}
