<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
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
            $categories =  $form->get('categories')->getData();

            // $product->setStock(-5);

            foreach($categories as $category) {
                $category->addProduct($product);
            }

            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Le produit a bien été ajouté');

            return $this->redirectToRoute('admin_product');
        }

        dump($product);

        return $this->render('admin_product/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/product/{id}', name: 'admin_product_edit')]
    public function edit(Product $product, ObjectManager $manager, Request $request, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $categoriesData =  $form->get('categories')->getData();
            $categories = $categoryRepository->findAll();

            foreach($categories as $category){
                $category->removeProduct($product);
            }

            foreach($categoriesData as $categoryData){
                $categoryData->addProduct($product);
            }

            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Le produit a bien été ajouté');

            return $this->redirectToRoute('admin_product');
        }

        dump($product);

        return $this->render('admin_product/edit.html.twig', [
            'form' => $form->createView(),
            'product'=>$product
        ]);
    }


    #[Route('/admin/product/remove/{id}', name: 'admin_product_remove')]
    public function remove(Product $product ,ObjectManager $manager): Response
    {
        $manager->remove($product);
        $manager->flush();

        return $this->redirectToRoute('admin_product');
    }
}
