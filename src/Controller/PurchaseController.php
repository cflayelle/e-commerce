<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Purchase;
use App\Entity\Status;
use App\Repository\PurchaseRepository;
use App\Repository\StatusRepository;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    #[Route('/commandes', name: 'app_purchase')]
    public function index(PurchaseRepository $purchaseRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $purchases = $purchaseRepository->findAllByUser($this->getUser()->getId());

        return $this->render('purchase/index.html.twig', [
            "purchases" => $purchases
        ]);
    }


    #[Route('/purchase/pay', name: 'app_purchase_pay')]
    public function pay(ObjectManager $manager, StatusRepository $statusRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cart = $this->getUser()->getCurrentCart();

        //vérifier produits < stock
        // foreach($cart->getCartElements() as $cartElement){
        //     if($cartElement->getQuantity() > $cartElement->getProduct()->getStock()){

        //         $this->addFlash('success',"Désolé, Un ou plusieurs produits ne sont plus disponible");
        //         return $this->redirectToRoute('app_cart');
        //     }
        // }

        $manager->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            //Retirer les stocks 
            foreach ($cart->getCartElements() as $cartElement) {
                $product =  $cartElement->getProduct();
                $productQuantity = $cartElement->getQuantity();

                $product->setStock($product->getStock() - $productQuantity);
            }

            $purchase = new Purchase();

            $purchase->setPurchaseDate(new DateTime());
            $purchase->setCart($cart);

            $status = $statusRepository->findOneBy([]);
            if (!$status) {
                $status = new Status();
                $status->setName("en cours");

                $manager->persist($status);
            }
            $purchase->setStatus($status);

            $manager->persist($purchase);

            $newCurrentCart = new Cart();
            $newCurrentCart->setCreatedAt(new DateTime());
            $newCurrentCart->setUser($this->getUser());


            $manager->persist($newCurrentCart);

            
            $manager->flush();

            $this->addFlash('success', 'Votre commande a bien été effectuée');

            $manager->getConnection()->commit();
        } catch (Exception $e) {
            $manager->getConnection()->rollBack();
            return $this->redirectToRoute("product");
            // throw $e;
        }

        return $this->redirectToRoute('app_cart');
    }
}
