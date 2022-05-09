<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories/{page}', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository,$page=1): Response
    {
   
        $pageSize = 10;

        $categories = $categoryRepository->getAll($page,$pageSize);

        if($page < 1){
            $page = 1;
        }

        $totalList = count($categories);
        $maxPages = ceil($totalList / $pageSize);
        if($page > $maxPages){
            $page=$maxPages;
        }

        return $this->render('category/index.html.twig', [
            "categories" => $categories,
            'totalList' => $totalList,
            'currentPage' => $page,
            "maxPages"=>$maxPages,
            "pathName"=>"app_category"
        ]);
    }
}
