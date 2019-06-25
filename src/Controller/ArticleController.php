<?php

namespace App\Controller;

use App\Service\slugify;
use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        $articles = $articleRepository->findAllWithCategoriesAndTags();
        //   dd($articles);

        return $this->render('article/index.html.twig', ['articles' => $articles]);
    }


    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     * @IsGranted("ROLE_AUTHOR")
     */
    public function new(Request $request, Slugify $slugify, \Swift_Mailer $mailer): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($article->getTitle());
            $article->setSlug($slug);
            $article->setAuthor($user);
            $entityManager->persist($article);
            $entityManager->flush();

            $message = (new \Swift_Message('n nouvel article vient d\'être publié sur ton blog !'))
                ->setFrom($this->getParameter('mailer_from'))
                ->setTo($this->getParameter('mailer_from'))
                ->setBody(
                    $this->renderView(
                        'email/article_newsletter.html.twig',
                        ['article' => $article]
                    ),
                    'text/html'
                );
            $mailer->send($message);

            $this->addFlash('secondary', 'The new article has been created');

            return $this->redirectToRoute('app_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'isFavorite'=>$this->getUser()->isFavorite($article)
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_AUTHOR")
     */
    public function edit(Request $request, Article $article, slugify $slugify): Response
    {
        if ($this->getUser() === $article->getAuthor() || in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {

            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid() ) {
                $article->setSlug($slugify->generate($article->getTitle()));
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'Article has been modified succesfully');

                return $this->redirectToRoute('article_index', [
                    'id' => $article->getId(),
                ]);
            }

            return $this->render('article/edit.html.twig', [
                'article' => $article,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_index');
        }
    }


    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash('danger', 'Votre article a bien été supprimé ');
        }

        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/{id}/favorite",name="article_favorite")
     */
    public function favorite(Request $request, Article $article, EntityManagerInterface $entityManager) :Response
    {
        if ($this->getUser()->getFavorite()->contains($article)) {
            $this->getUser()->removeFavorite($article)   ;
        }
        else {
            $this->getUser()->addFavorite($article);
        }
        $entityManager->flush();
        return $this->json([
            'isFavorite' => $this->getUser()->isFavorite($article)
        ]);
    }


}
