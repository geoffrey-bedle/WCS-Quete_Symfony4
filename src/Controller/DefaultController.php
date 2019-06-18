<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{


    /**
     * @Route("/",name="app_index")
     */
    public function index(ArticleRepository $articleRepository)
    {
        $lastArticles = $articleRepository->fiveLastArticles();
        return $this->render('default.html.twig',['lastArticles'=>$lastArticles]);
    }


    public function navbarLink(TagRepository $tagRepository, CategoryRepository $categoryRepository)
    {
        $tags = $tagRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('inc/_navbar.html.twig', ['tags' => $tags, 'categories' => $categories]);
    }




}