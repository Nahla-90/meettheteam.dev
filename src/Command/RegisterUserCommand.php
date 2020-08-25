<?php
// src/AppBundle/Command/GreetCommand.php
namespace App\Command;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\User\User;

class RegisterUserCommand extends Command
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        // 3. Update the value of the private entityManager variable through injection
        $this->entityManager = $entityManager;

        parent::__construct();
    }
    protected function configure()
    {
        $this->setName('register:user')
            ->setDescription('Register User')
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'enter email?'
            )
            ->addArgument(
                'fullname',
                InputArgument::REQUIRED,
                'enter fullname?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $fullname = $input->getArgument('fullname');

        $user=new Users();
        $user->setEmail($email);
        $user->setFullname($fullname);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('User Registered successfuly');
    }
}
