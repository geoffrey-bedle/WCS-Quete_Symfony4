<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog",name="blog_index")
     */
    public function index()
    {
        return $this->render('blog/list.html.twig', ['owner' => 'Geoffrey']);
    }

    /**
     * @Route("/blog/page/{page}", name="blog_list")
     */
    public function list($page)
    {
        return $this->render('blog/index.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/blog/show/{slug<[a-z0-9-]+>}",methods={"GET"}, name="blog_show")
     */
    public function show($slug = 'article sans titre')
    {
        $slug = ucwords(str_replace("-", " ", $slug));
        return $this->render('blog/show.html.twig', ['slug' => $slug]);
    }
}