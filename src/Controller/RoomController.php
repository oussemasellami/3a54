<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
{
    #[Route('/room', name: 'room')]
    public function index(): Response
    {
        return $this->render('room/index.html.twig', [
            'controller_name' => 'RoomController',
        ]);
    }
    #[Route('/addroom', name: 'addroom')]
    public function addcar(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($room);
            $em->flush();
        }
        return $this->renderForm('room/add.html.twig', [
            'f' => $form
        ]);
    }
    #[Route('/showroom', name: 'showroom')]
    public function showroom(RoomRepository $roomRepository): Response
    {
        $room = $roomRepository->findAll();
        return $this->render('room/showroom.html.twig', [
            'room' => $room
        ]);
    }

    #[Route('/editroom/{id}', name: 'editroom')]
    public function editroom($id, RoomRepository $roomRepository, Request $req, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        // var_dump($id) . die();
        $dataid = $roomRepository->find($id);
        // var_dump($dataid) . die();
        $form = $this->createForm(RoomType::class, $dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('showroom');
        }

        return $this->renderForm('room/edit.html.twig', [
            'f' => $form
        ]);
    }

    #[Route('/deleteroom/{id}', name: 'deleteroom')]
    public function deleteroom(
        $id,
        RoomRepository $roomRepository,
        ManagerRegistry $managerRegistry
    ): Response {
        $em = $managerRegistry->getManager();
        $dataid = $roomRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('showroom');
    }
}
