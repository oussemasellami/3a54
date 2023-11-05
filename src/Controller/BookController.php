<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/showbook', name: 'showbook')]
    public function showbook(BookRepository $bookRepository): Response
    {
        $book = $bookRepository->findAll();
        return $this->render('book/showbook.html.twig', [
            'book' => $book
        ]);
    }

    #[Route('/addbook', name: 'addbook')]
    public function addbook(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($book);
            $em->flush();
        }
        return $this->renderForm('book/addbook.html.twig', [
            'f' => $form
        ]);
    }
}
