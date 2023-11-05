<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\MinmaxType;
use App\Form\SearchType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/orderauthor', name: 'orderauthor')]
    public function orderauthor(AuthorRepository $authorRepository): Response
    {
        $ssssssss = $authorRepository->orderbydesc();
        //var_dump($ssssssss) . die();
        return $this->render('author/order.html.twig', [
            'author' => $ssssssss
        ]);
    }

    #[Route('/showdbauthor', name: 'showdbauthor')]
    public function showdbauthor(AuthorRepository $authorRepository, Request $request): Response
    {
        $author = $authorRepository->findAll();
        // $form = $this->createForm(MinmaxType::class);
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        //var_dump($form->getData()) . die();
        if ($form->isSubmitted()) {
            // $min = $form->get('min')->getData();
            // $max = $form->get('max')->getData();
            $username = $form->get('username')->getData();
            dump($form->getData()). die();
            $authors = $authorRepository->searchauthor($username);
            // $authors = $authorRepository->searchminmax($min,$max);
            //var_dump($authors) . die();
            return $this->render('author/showdbauthor.html.twig', array('author' => $authors,  'f' => $form->createView()));
        }
        return $this->renderForm('author/showdbauthor.html.twig', [
            'f' => $form,
            'author' => $author
        ]);
    }

    #[Route('/addauthor', name: 'addauthor')]
    public function addauthor(ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $author = new Author();
        $author->setUsername("3a58");
        $author->setEmail("3a58@esprit.tn");
        $em->persist($author);
        $em->flush();
        return new Response("great add");
    }

    #[Route('/addformauthor', name: 'addformauthor')]
    public function addformauthor(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {

            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('showdbauthor');
        }
        return $this->renderForm('author/addformauthor.html.twig', [
            'f' => $form
        ]);
    }

    #[Route('/showbooksauthor/{id}', name: 'showbooksauthor')]
    public function showbooksauthor($id, AuthorRepository $authorRepository): Response
    {
        $listbook = $authorRepository->showbooksauthor($id);
        // var_dump($listbook) . die();
        return $this->render('author/showbooksauthor.html.twig', [
            'a' => $listbook
        ]);
    }
    #[Route('/editauthor/{id}', name: 'editauthor')]
    public function editauthor($id, AuthorRepository $authorRepository, Request $req, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        // var_dump($id) . die();
        $dataid = $authorRepository->find($id);
        // var_dump($dataid) . die();
        $form = $this->createForm(AuthorType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('showdbauthor');
        }

        return $this->renderForm('author/editauthor.html.twig', [
            'x' => $form
        ]);
    }

    #[Route('/deleteauthor/{id}', name: 'deleteauthor')]
    public function deleteauthor(
        $id,
        AuthorRepository $authorRepository,
        ManagerRegistry $managerRegistry
    ): Response {
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

    #[Route('/showtableauthor', name: 'app_showtableauthor')]
    public function showtableauthor(): Response
    {

        return $this->render('author/list.html.twig', [
            'author' => $this->authors
        ]);
    }

    #[Route('/showbyidauthor/{id}', name: 'showbyidauthor')]
    public function showbyidauthor($id): Response
    {

        //var_dump($id) . die();

        $x = null;
        foreach ($this->authors as $authorD) {
            if ($authorD['id'] == $id) {
                $x = $authorD;
            }
        }
        //var_dump($x) . die();

        return $this->render('author/showbyidauthor.html.twig', [
            'author' => $x
        ]);
    }
}