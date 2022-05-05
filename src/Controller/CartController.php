<?php

namespace App\Controller;

use App\Entity\CartElement;
use App\Entity\Product;
use App\Repository\CartElementRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartElementRepository $cartElementRepository, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        // $cartElements = $cartElementRepository->findAll();

        $cart = $this->getUser()->getCurrentCart();

        $cartElements = $cart->getCartElements();

        // dd($cart);
        return $this->render('cart/index.html.twig', [
            'cartElements' => $cartElements,
        ]);
    }
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(Product $product, ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $cart = $this->getUser()->getCurrentCart();

        // Ajouter un produit existant dans le panier
        foreach ($cart->getCartElements() as $cartElement) {
            if ($cartElement->getProduct() === $product) {
                if ($cartElement->getQuantity() <= $product->getStock()) {
                    $cart->setQuantityTotal($cart->getQuantityTotal() + 1);
                    $cartElement->setQuantity($cartElement->getQuantity() + 1);
                    $cart->setTotalPrice($cart->getTotalPrice() + $product->getPrice());

                    $manager->flush();
                }
                return $this->redirectToRoute('app_cart');
            }
        }

        // Ajouter un nouveau produit dans le panier
        $cartElement = new CartElement();
        $cartElement->setQuantity(1);
        $cartElement->setProduct($product);
        $cart->setTotalPrice($cart->getTotalPrice() + $product->getPrice());
        $cartElement->setCart($cart);

        $cart->setQuantityTotal($cart->getQuantityTotal() + 1);


        $manager->persist($cartElement);


        $manager->flush();

        return $this->redirectToRoute('app_cart');
    }
    
    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(Product $product, ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cart = $this->getUser()->getCurrentCart();

        foreach ($cart->getCartElements() as $cartElement) {
            if ($cartElement->getProduct() === $product) {
                if($cartElement->getQuantity() <= 1){
                    $cart->removeCartElement($cartElement);
                }
                if ($cartElement->getQuantity() > 0) {                   
                    $cart->setQuantityTotal($cart->getQuantityTotal() - 1);
                    $cartElement->setQuantity($cartElement->getQuantity() - 1);
                    $cart->setTotalPrice($cart->getTotalPrice() - $product->getPrice());
                }
            }
        }

        $manager->flush();

        return $this->redirectToRoute('app_cart');
    }
    #[Route('/cart/delete/{id}', name: 'app_cart_delete')]
    public function delete(Product $product, ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cart = $this->getUser()->getCurrentCart();

        foreach ($cart->getCartElements() as $cartElement) {
            if ($cartElement->getProduct() === $product) {
                $cart->setQuantityTotal($cart->getQuantityTotal() - $cartElement->getQuantity());
                $cart->setTotalPrice($cart->getTotalPrice() - $product->getPrice() * $cartElement->getQuantity());
                $cart->removeCartElement($cartElement);
            }
        }

        $manager->flush();

        return $this->redirectToRoute('app_cart');
    }
}
