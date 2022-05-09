<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Form\PurchaseType;
use App\Repository\PurchaseRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/admin/commande/edit/{id}', name: 'admin_purchase_edit')]
    public function edit(Purchase $purchase, Request $request, ObjectManager $manager): Response
    {
        $form = $this->createForm(PurchaseType::class, $purchase);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($purchase);
            $manager->flush();

            $this->addFlash('success', 'La commande a bien été modifiée');

            return $this->redirectToRoute('admin_purchase');
        }

        return $this->render('admin_purchase/edit.html.twig', [
            'form' => $form->createView(),
            'purchase'=>$purchase
        ]);
    }
}
