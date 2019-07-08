<?php


namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    /**
     * @Route({
     *     "en" : "/category/new",
     *     "fr" : "/categorie/ajout",
     *     "es" : "/categoria/crear",
     *      },
     *     name="category_add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', 'Category has been add');
        }

        return $this->render('category/addcategory.html.twig',
            ['form' => $form->createView(),
                'categories' => $categories]);
    }

    /**
     * @Route("/categories", name="categories_show")
     */
    public function showCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/category.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("category/{id}", name="category_show")
     */
    public function showCategory(Category $category)
    {
        return $this->render('category/show.html.twig', ['category' => $category]);
    }

}