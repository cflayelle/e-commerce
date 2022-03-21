<?php

namespace App\Controller;

use App\Repository\StatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStatusController extends AbstractController
{
    #[Route('/admin/status', name: 'app_admin_status')]
    public function index(StatusRepository $statusRepository): Response
    {
        $statuses = $statusRepository->findAll();

        return $this->render('admin_status/index.html.twig', compact("statuses"));
    }
}
