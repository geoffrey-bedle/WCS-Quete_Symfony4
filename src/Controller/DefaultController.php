<?php


namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    /**
     * @Route("/",name="app_index")
     */
    public function index()
    {
        return $this->render('default.html.twig');
    }


    public function navbarLink(TagRepository $tagRepository, CategoryRepository $categoryRepository)
    {
        $tags = $tagRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('inc/_navbar.html.twig',['tags'=>$tags,'categories'=>$categories]);
    }


}