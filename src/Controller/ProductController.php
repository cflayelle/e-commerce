<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    /**
     * @Route("/produits", name="product")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findProductsAvailable();

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }


    /**
     * @Route("/categorie/{id}/produits", name="categorie_products")
     */
    public function showProduct(Category $category): Response
    {
        $products = $category->getProducts();

        return $this->render('product/showCategorieProducts.html.twig', [
            'products' => $products
        ]);
    }
}
