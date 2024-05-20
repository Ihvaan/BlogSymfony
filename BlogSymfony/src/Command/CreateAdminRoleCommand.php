<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Entity\User;
use App\Repository\UserRepository;



#[AsCommand(
    name: 'app:create-role-admin',
    description: 'create admin',
)]
class CreateAdminRoleCommand extends Command
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Promotes a user to admin role')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user to promote to admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $emailUser = $input->getArgument('email');

        try {
            $user = $this->userRepository->findOneBy(['email' => $emailUser]);

            if (!$user instanceof UserInterface) {
                throw new \Exception('User not found');
            }

            $user->setRoles(['ROLE_ADMIN']);
            $this->entityManager->flush();

            $output->writeln(sprintf('User with email "%s" has been promoted to admin role.', $emailUser));
            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $output->writeln(sprintf('User with email "%s" not found.', $emailUser));
            return Command::FAILURE;
        }
    }
}
