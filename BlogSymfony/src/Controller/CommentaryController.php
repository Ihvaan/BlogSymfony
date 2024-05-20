<?php

namespace App\Controller;

use App\Entity\Commentary;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;

class CommentaryController extends AbstractController
{
    #[Route('/commentary/create', name: 'comment_create', methods: ['POST'])]

    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentary();
        $form = $this->createForm(Commentary::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $description = $form->get('Description')->getData();
            $date = $form->get('Date')->getData();
            $auteur = $form->get('Auteur')->getData();
            $titre = $form->get('Title')->getData();
            $article = $form->get('articles')->getData();
            $commentaire->setDescription($description);
            $commentaire->setDate($date);
            $commentaire->setAuthor($auteur);
            $commentaire->setTitle($titre);
            $commentaire->setArticle($article);
            $entityManager->persist($commentaire);
            $entityManager->flush();
            return $this->redirectToRoute('app_accueil');
        }
        return $this->render('commentaires/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
