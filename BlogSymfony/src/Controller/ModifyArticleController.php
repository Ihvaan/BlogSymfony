<?php

namespace App\Controller;

use App\Entity\Article as EntityArticle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ModifyArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    #[Route('/modify/article/{id}', name: 'article_modify')]

    public function modify(int $id, ArticleRepository $articlesRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->articleRepository = $articlesRepository;
        $article = $this->articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $form = $this->createForm(EntityArticle::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('articles/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
