<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Product;
use App\Form\CommentType;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\Persistence\ObjectManager;
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

        $totalList = count($products);
        $maxPages = ceil($totalList / $pageSize);
        if($page > $maxPages){
            $page=$maxPages;
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'totalList' => $totalList,
            'currentPage' => $page,
            'maxPages'=>$maxPages,
            'pathName' => "product"
        ]);
    }
    
    /**
     * @Route("/produit/{id}", name="show_product")
     */
    public function oneProduct(Product $product,ObjectManager $manager, Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() && $this->getUser()){
            $comment->setCreatedAt(new DateTime())
                    ->setProduct($product)
                    ->setAuthor($this->getUser())
                    ;
            $manager->persist($comment);
            $manager->flush();
            
            $this->addFlash('success', 'Votre commentaire a bien ??t?? ajout??');
        }
        $comments = $product->getComments();
        
        return $this->render('product/oneProducts.html.twig', [
            'product' => $product,
            'form'=> $form->createView(),
            'comments'=>$comments,
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
            'category' => $category,
        ]);
    }
}
