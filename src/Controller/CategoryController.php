<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_TVSHOW_ADMIN")
 * @Route("/admin")
 */
class CategoryController extends AbstractController
{

    /**
     * @Route("/category", name="category_admin")
     */
    public function index(CategoryRepository $categoryRepository)
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/categories", name="category_list")
     */
    public function list()
    {
        /** @var CategoryRepository $repository */ // dot bloc : pour présiser à VSC le repository utiliser afin d'eviter le surlignement de la methode findAllOrderByLabel()
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAllOrderByLabel();

        return $this->render('category/list.html.twig', ["categories" => $categories]);
    }

    /**
     * @Route("/category/{id}", name="category_view", requirements={"id"="\d+"})
     */
    public function view($id)
    {
        /** @var CategoryRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->findOneWithTvShows($id);
        $categories = $repository->findAllOrderByLabel();

        return $this->render('category/view.html.twig', [
            "category" => $category,
            "categories" => $categories
            ]);
    }

    /**
     * @Route("/category/add", name="category_add")
     */
    public function add(Request $request)
    {
        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if($categoryForm->isSubmitted() && $categoryForm->isValid()){

        
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();
            $this->addFlash("success", "La categorie a bien été ajoutée");
            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/add.html.twig', 
        [
            'categoryForm' => $categoryForm->createView()
        ]);

    }

        /**
     * @Route("/category/{id}/update", name="category_update", requirements={"id"="\d+"})
     */
    public function update(Category $category, Request $request)
    {

        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);
        if($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash("success", "La saison a bien été ajoutée");
            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/update.html.twig', [
            "categoryForm" => $categoryForm->createView(),
            "category" => $category
        ]);
    }



    /**
     * @Route("/category/{id}/delete", name="category_delete", requirements={"id"="\d+"})
     */

    public function delete(Category $category)
    {
           $manager = $this->getDoctrine()->getManager();
           $manager->remove($category);
           $manager->flush();
           $this->addFlash("success", "La catégorie a bien été supprimée");
           return $this->redirectToRoute('tv_show_list');
    } 


}