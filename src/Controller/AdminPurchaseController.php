<?php

namespace App\Controller;

use App\Repository\PurchaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPurchaseController extends AbstractController
{
    #[Route('/admin/commandes', name: 'admin_purchase')]
    public function index(PurchaseRepository $purchaseRepository): Response
    {
        $purchases = $purchaseRepository->findAll();
        return $this->render('admin_purchase/index.html.twig', [
            'purchases' => $purchases,
        ]);
    }
}
