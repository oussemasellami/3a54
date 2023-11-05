<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    #[Route('/car', name: 'app_car')]
    public function index(): Response
    {
        return $this->render('car/index.html.twig', [
            'controller_name' => 'CarController',
        ]);
    }
    #[Route('/addcar', name: 'addcar')]
    public function addcar(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($car);
            $em->flush();
        }
        return $this->renderForm('car/add.html.twig', [
            'f' => $form
        ]);
    }
    #[Route('/showcar', name: 'showcar')]
    public function showroom(CarRepository $carRepository): Response
    {
        $car = $carRepository->findAll();
        return $this->render('car/showcar.html.twig', [
            'car' => $car
        ]);
    }

    #[Route('/editcar/{id}', name: 'editcar')]
    public function editcar($id, CarRepository $carRepository, Request $req, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        // var_dump($id) . die();
        $dataid = $carRepository->find($id);
        // var_dump($dataid) . die();
        $form = $this->createForm(CarType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('showcar');
        }

        return $this->renderForm('car/editcar.html.twig', [
            'f' => $form
        ]);
    }

    #[Route('/deletecar/{id}', name: 'deletecar')]
    public function deletecar(
        $id,
        CarRepository $carRepository,
        ManagerRegistry $managerRegistry
    ): Response {
        $em = $managerRegistry->getManager();
        $dataid = $carRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('showcar');
    }
}