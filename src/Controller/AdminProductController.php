<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;


class AdminProductController extends AbstractController
{
    #[Route('/admin/product', name: 'admin_product')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('admin_product/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/admin/product/new', name: 'admin_product_new')]
    public function new(ObjectManager $manager, Request $request): Response
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            $manager->persist($product);
            $manager->flush();
        
            $this->addFlash('success','Le produit a bien été ajouté');

            return $this->redirectToRoute('admin_product');
        }

        dump($product);

        return $this->render('admin_product/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
