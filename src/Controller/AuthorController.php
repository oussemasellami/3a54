<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{

    public $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
    );
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showdbauthor', name: 'showdbauthor')]
    public function showdbauthor(AuthorRepository $authorRepository): Response
    {
        $author = $authorRepository->findAll();
        return $this->render('author/showdbauthor.html.twig', [
            'author' => $author
        ]);
    }

    #[Route('/addauthor', name: 'addauthor')]
    public function addauthor(ManagerRegistry $managerRegistry): Response
    {
        $x = $managerRegistry->getManager();
        $author = new Author();
        $author->setUsername("3a54new");
        $author->setEmail("3a54new@esprit.tn");
        $x->persist($author);
        $x->flush();
        return new Response(" great add");
    }

    #[Route('/addformauthor', name: 'addformauthor')]
    public function addformauthor(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $x = $managerRegistry->getManager();
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $x->persist($author);
            $x->flush();

            return $this->redirectToRoute('showdbauthor');
        }
        return $this->renderForm('author/addformauthor.html.twig', [
            'f' => $form
        ]);
    }

    #[Route('/editauthor/{id}', name: 'editauthor')]
    public function editauthor($id, AuthorRepository $authorRepository, ManagerRegistry $managerRegistry, Request $req): Response
    {
        //var_dump($id) . die();
        $x = $managerRegistry->getManager();
        $dataid = $authorRepository->find($id);
        // var_dump($dataid) . die();
        $form = $this->createForm(AuthorType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $x->persist($dataid);
            $x->flush();
            return $this->redirectToRoute('showdbauthor');
        }
        return $this->renderForm('author/editauthor.html.twig', [
            'x' => $form
        ]);
    }

    #[Route('/deleteauthor/{id}', name: 'deleteauthor')]
    public function deleteauthor($id, ManagerRegistry $managerRegistry, AuthorRepository $authorRepository): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $authorRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('showdbauthor');
    }

    #[Route('/showauthor/{name}', name: 'app_showauthor')]
    public function showauthor($name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name
        ]);
    }

    #[Route('/showtableauthor', name: 'showtableauthor')]
    public function showtableauthor(): Response
    {



        return $this->render('author/showtableauthor.html.twig', [
            'authors' => $this->authors
        ]);
    }

    #[Route('/showbyidauthor/{id}', name: 'showbyidauthor')]
    public function showbyidauthor($id): Response
    { //var_dump($id).die();

        $author = null;
        foreach ($this->authors as $authorD) {
            if ($authorD['id'] == $id) {
                $author = $authorD;
            }
        }
        //var_dump($author) . die();



        return $this->render('author/showbyidauthor.html.twig', [
            'author' => $author
        ]);
    }
}
