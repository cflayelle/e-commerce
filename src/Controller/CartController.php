<?php

namespace App\Controller;

use App\Entity\CartElement;
use App\Entity\Product;
use App\Repository\CartElementRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartElementRepository $cartElementRepository): Response
    {
        $cartElements = $cartElementRepository->findAll();

        return $this->render('cart/index.html.twig', [
            'cartElements' => $cartElements
        ]);
    }
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(Product $product, ObjectManager $manager): Response
    {
        if (count($product->getCartElements()) > 0) {
            foreach ($product->getCartElements() as $cartElement) {
                $cartElement->setQuantity($cartElement->getQuantity() + 1);
                $manager->persist($cartElement);
            }
        } else {
            $cartElement = new CartElement();
            $cartElement->setQuantity(1);
            $cartElement->setProduct($product);

            $manager->persist($cartElement);
        }

        $manager->flush();

        return $this->redirectToRoute('app_cart');
    }
}
