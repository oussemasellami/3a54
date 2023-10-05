<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
