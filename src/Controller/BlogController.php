<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\ArticleSearchType;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;
use App\Entity\Category;


/**
 * Class BlogController
 * @package App\Controller
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index")
     */
    public function index(Request $request): Response
    {
        $category = new Category();
        $categoryform = $this->createForm(
            CategoryType::class,
            $category);




        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        if (!$articles) {
            throw  $this->createNotFoundException('No article found in article\'s table.');
        }

        return $this->render('category/index.html.twig', ['articles' => $articles, 'categories' => $categories,

            'categoryform' => $categoryform->createView()]);
    }

    /**
     * @Route("/page/{page}", name="blog_list")
     */
    public function list($page)
    {
        return $this->render('category/list.html.twig', ['page' => $page]);
    }
    /*
        /**
         * Getting a article with a formatted slug for title
         *
         * @param string $slug The slugger
         *
         * @Route("/{slug<^[a-z0-9-]+$>}",
         *     defaults={"slug" = null},
         *     name="blog_show")
         * @return Response A response instance

        public
        function show(?string $slug): Response
        {
            if (!$slug) {
                throw $this
                    ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
            }

            $slug = preg_replace(
                '/-/',
                ' ', ucwords(trim(strip_tags($slug)), "-")
            );

            $article = $this->getDoctrine()
                ->getRepository(Article::class)
                ->findOneBy(['title' => mb_strtolower($slug)]);

            if (!$article) {
                throw $this->createNotFoundException(
                    'No article with ' . $slug . ' title, found in article\'s table.'
                );
            }
            $category = $article->getCategory();
            return $this->render(
                'category/show.html.twig',
                [
                    'article' => $article,
                    'slug' => $slug,
                    'category' => $category
                ]
            );
        }
    */


    /**
     *
     * @return Response
     * @Route("/category/{name}", name="show_category")
     */
    public function showByCategory(Category $category)
    {
        $articles = $category->getArticles();

        return $this->render(
            'category/category.html.twig', ['categoryArticles' => $articles, 'category' => $category]
        );
    }


    /**
     *
     * @Route("/tag/show/{name}", name="tag")
     */
    public function showByTag(Tag $tag)
    {
        $articles = $tag->getArticles();
        return $this->render('category/showtag.html.twig', ['articles' => $articles, 'tag' => $tag]);
    }
}

