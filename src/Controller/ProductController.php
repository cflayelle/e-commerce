<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="product")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findProductsAvailable();

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }
}
