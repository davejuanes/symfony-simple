<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(CommentForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        $comments = $entityManager->getRepository(Comment::class)->findBy([], [
            'id' => 'DESC',
        ]);

        return $this->render('home.html.twig', [
            // 'comments' => $entityManager->getRepository(Comment::class)->findAll(),
            'comments' => $comments,
            'form'     => $form->createView(),
        ]);
    }
}
