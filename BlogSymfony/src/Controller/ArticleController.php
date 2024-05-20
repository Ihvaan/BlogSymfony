<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article_create')]

    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(Article::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $description = $form->get('Description')->getData();
            $date = $form->get('Date')->getData();
            $auteur = $form->get('Auteur')->getData();
            $titre = $form->get('Title')->getData();
            $article->setDescription($description);
            $article->setDate($date);
            $article->setAuthor($auteur);
            $article->setTitle($titre);
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('app_accueil');
        }
        return $this->render('articles/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
