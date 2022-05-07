<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Category;
use App\Entity\Product;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
     /**
     * @Route("/produits/{page}", name="product")
     */
    public function index(ProductRepository $repository, Request $request,$page=1)
    {
        $pageSize = 8;
        if($page < 1){
            $page = 1;
        }
        $data = new SearchData();
        
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        $products = $repository->findSearch($data,$page,$pageSize);

        $totalProducts = count($products);
        $maxPages = ceil($totalProducts / $pageSize);
        if($page > $maxPages){
            $page=$maxPages;
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
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
