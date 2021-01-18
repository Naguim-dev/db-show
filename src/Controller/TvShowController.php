<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\TvShow;
use App\Form\TvShowType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
     * @Route("/tvshow")
     */


class TvShowController extends AbstractController
{

    /**
     * @Route("/list", name="tv_show_list")
     */
    public function list(Request $request, PaginatorInterface $paginator)
    {
        $search = $request->query->get('search');

        /** @var TvShowRepository $repository */

        $repository = $this->getDoctrine()->getRepository(TvShow::class)->findByTitle($search);

    
        $tvShows = $paginator->paginate(
            $repository, // On passe les données
            $request->query->getInt('page', 1),
            3
        );

        /** @var CategoryRepository $categoryRepository */

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $categoryRepository->findAllOrderByLabel();

        return $this->render('tv_show/list.html.twig', [
            "tvShows" => $tvShows,
             "categories" => $categories
             ]);
    }


    /**
     * @Route("/{id}", name="tv_show_view", requirements={"id"="\d+"}))
     */
    public function view($id) // ou view(TvShow $tvShow) et supprimer $repository = $this->getDoctrine()->->getRepository(TvShow::class); $tvShow = $repository->find($id);
    {
        /** @var tvShowRepository $repository */
        $repository = $this->getDoctrine()->getRepository(TvShow::class);
        $tvShow = $repository->findWithCollections($id);
        

        return $this->render('tv_show/view.html.twig', [
            "tvShow" => $tvShow,
            ]);
    }
 

}
