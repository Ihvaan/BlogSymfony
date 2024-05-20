<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Commentary;
use App\Form\CommentaryType;
use App\Repository\CommentaryRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ModifyCommentsController extends AbstractController
{
    private CommentaryRepository $commentairesRepository;

    #[Route('/modify/commentaires/{id}', name: 'commentaires_modify')]
    public function modify(int $id, CommentaryRepository $commentairesRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->commentairesRepository = $commentairesRepository;
        $commentaire = $this->commentairesRepository->find($id);

        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire not found');
        }

        $form = $this->createForm(Commentary::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('commentaires/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
