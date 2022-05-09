<?php

namespace App\Controller;

use App\Entity\Status;
use App\Form\StatusType;
use App\Repository\StatusRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStatusController extends AbstractController
{
    #[Route('/admin/status', name: 'app_admin_status')]
    public function index(StatusRepository $statusRepository,ObjectManager $manager, Request $request): Response
    {
        $statuses = $statusRepository->findAll();

        $status = new Status();
        $form = $this ->createForm(StatusType::class, $status);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($status);
            $manager->flush();

            $this->addFlash('success', 'Le statut a bien été ajouté');
            return $this->redirectToRoute('app_admin_status');
        }

        return $this->render('admin_status/index.html.twig', [
            "statuses" => $statuses,
            'form' => $form->createView()
        ]);
    } 
}
