<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicController extends AbstractController
{
    //1 ArticleRepository à ajouter en auto-wiring
    //1.5 On crée une Route accueil (qui va afficher les artciles)
    //2 On charge les articles avec ArticleRpository
    //3 On passe les articles à la vue TWIG
    //4 On modifie la vue TWIG pour avoir les articles visibles.

    //5 On crée une AUTRE Route Article (qui va afficher un article et ses commentaires)
    //6 On charge un article et ses commentaires avec ArticleRepository
    //7 On passe les infos à la vue TWIG
    //8 On modifie cette vue TWIG

    //9 On crée un lien dans la vue TWIG accueil, pour aller vers la route Article

    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        //parent::__construct();
        $this->articleRepository = $articleRepository;
    }

    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        $article = $this->articleRepository->findAll();

        return $this->render('public/index.html.twig', [
            'article' => $article
        ]);
    }

    #[Route('/article/{id}', name: 'app_article')]
    public function article(int $id): Response
    {
        $article = $this->articleRepository->find($id);

        return $this->render('public/article.html.twig', [
            'article' => $article
        ]);
    }
}
