<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Purchase;
use App\Entity\Status;
use App\Repository\StatusRepository;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    #[Route('/purchase', name: 'app_purchase')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('purchase/index.html.twig', [
            'controller_name' => 'PurchaseController',
        ]);
    }

    #[Route('/purchase/pay', name: 'app_purchase_pay')]
    public function pay(ObjectManager $manager, StatusRepository $statusRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cart = $this->getUser()->getCurrentCart();

        //vérifier produits < stock
        foreach($cart->getCartElements() as $cartElement){
            if($cartElement->getQuantity() > $cartElement->getProduct()->getStock()){

                $this->addFlash('success',"Désolé, Un ou plusieurs produits ne sont plus disponible");
                return $this->redirectToRoute('app_cart');
            }
        }

        //Retirer les stocks 
        foreach($cart->getCartElements() as $cartElement){
            $product =  $cartElement->getProduct();
            $productQuantity = $cartElement->getQuantity();

            $product->setStock($product->getStock() - $productQuantity);
        }

        $purchase = new Purchase();

        $purchase->setPurchaseDate(new DateTime());
        $purchase->setCart($cart);

        $status = $statusRepository->find(1);
        if(!$status){
            $status = new Status();
            $status->setName("en cours");
            
            $manager->persist($status);
        }
        $purchase->setStatus($status);

        $newCurrentCart = new Cart();
        $newCurrentCart->setCreatedAt(new DateTime());
        $newCurrentCart->setUser($this->getUser());


        $manager->persist($newCurrentCart);

        $manager->persist($purchase);
        $manager->flush();

        $this->addFlash('success','Votre commande a bien été effectuée');
        
        return $this->redirectToRoute('app_cart');

    }
}
