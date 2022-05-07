<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
  
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findProductsAvailable();

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/produits/{page}", name="product")
     */
	public function workWithOrder(ProductRepository $productRepository,$page=1){
		// Get the first page of orders
        $pageSize = 8;
        if($page < 1){
            $page = 1;
        }
		$paginatedResult = $productRepository->getProductsAvailable($page,$pageSize);
        
		$totalProducts = count($paginatedResult);
        $maxPages = ceil($totalProducts / $pageSize);
        if($page > $maxPages){
            $page=$maxPages;
        }

        return $this->render('product/index.html.twig', [
            'products' => $paginatedResult,
            'totalProducts' => $totalProducts,
            'currentPage' => $page,
            "maxPages"=>$maxPages
        ]);
	}
    
    /**
     * @Route("/produit/{id}", name="show_product")
     */
    public function oneProduct(Product $product): Response
    {
        return $this->render('product/oneProducts.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/categorie/{id}/produits", name="category_products")
     */
    public function showProduct(Category $category): Response
    {
        $products = $category->getProducts();

        return $this->render('product/showCategorieProducts.html.twig', [
            'products' => $products,
            'category' => $category
        ]);
    }
}
