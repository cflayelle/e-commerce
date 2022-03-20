<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    #[Route('/admin/categories', name: 'admin_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('admin_category/index.html.twig', [
            "categories" => $categories
        ]);
    }

    #[Route('/admin/category/new', name: 'admin_category_new')]
    public function new(ObjectManager $manager, Request $request): Response
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();
        
            $this->addFlash('success','La catégorie a bien été ajouté');

            return $this->redirectToRoute('admin_category');
        }

        dump($category);

        return $this->render('admin_category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/category/{id}', name: 'admin_category_edit')]
    public function edit(Category $category,ObjectManager $manager, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            echo "hello";
            $manager->persist($category);
            $manager->flush();
        
            $this->addFlash('success','La catégorie a bien été ajouté');

            return $this->redirectToRoute('admin_category');
        }

        dump($category);

        return $this->render('admin_category/edit.html.twig', [
            'form' => $form->createView(),
            'category'=>$category
        ]);
    }
}
