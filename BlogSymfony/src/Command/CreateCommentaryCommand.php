<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;
use App\Entity\Commentary;
use Doctrine\ORM\EntityManager;

#[AsCommand(
    name: 'app:CreateCommentary',
    description: 'Add a short description for your command',
)]
class CreateCommentaryCommand extends Command
{
    private ArticleRepository $articleRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        ArticleRepository $articleRepository
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->articleRepository = $articleRepository;
    }

    protected function configure(): void
    {
        $this->addArgument('nb_commentaire', InputArgument::REQUIRED, 'Nombre de commentaires');
        $this->addArgument('id_article', null, InputArgument::REQUIRED, 'Id de l\'article');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $idArticle = $input->getArgument('id_article');
        $article = $this->articleRepository->find($idArticle);

        if (!$article) {
            $io->error(sprintf('Impossible de trouver l\'article', $idArticle));
            return Command::FAILURE;
        }

        $nbCommentaires = $input->getArgument('nb_commentaire');

        for ($compteur = 0; $compteur < $nbCommentaires; $compteur++) {
            $io->comment('Creation commentaire' . $compteur);
            $commentaire = new Commentary();
            $commentaire->setContent("Commentaire " . $compteur);
            $commentaire->setAuthor('Ihvan');
            $commentaire->setDate(new \DateTime());
            $commentaire->setArticle($article);
            $this->entityManager->persist($commentaire);
        }

        $this->entityManager->flush();
        $io->success($compteur . ' commentaires créés !');
        return Command::SUCCESS;
    }
}
