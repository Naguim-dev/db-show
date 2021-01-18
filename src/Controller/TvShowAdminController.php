<?php

namespace App\Controller;

use App\Entity\TvShow;
use App\Form\TvShowType;
use App\Repository\TvShowRepository;
use App\Service\SluggerMethod;
use App\Service\Uploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @IsGranted("ROLE_TVSHOW_ADMIN")
 * @Route("/admin/tvshow")
 */
class TvShowAdminController extends AbstractController
{
    private $sluggerMethod;
    private $uploader;

    public function __construct(Uploader $uploader)
    {
        /** Sans le bundle https://github.com/antishov/StofDoctrineExtensionsBundle
         * $this->sluggerMethod = $sluggerMethod;
         */
        $this->uploader = $uploader;
    }


    /**
     * @Route("/", name="tvshow_admin")
     */
    public function index(TvShowRepository $tvShowRepository)
    {
        return $this->render('tv_show/index.html.twig', [
            'tvShows' => $tvShowRepository->findAll()
        ]);
    }



    /**
     * @Route("/add", name="tv_show_add")
     */
    public function add(Request $request)
    {
        $tvShow = new TvShow();
        $form = $this->createForm(TvShowType::class, $tvShow);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            /** Sans le bundle https://github.com/antishov/StofDoctrineExtensionsBundle
            * utilisé ce code pour sluggify le title depuis le controller TvShowController
            * $slug = $this->sluggerMethod->sluggifyText($tvShow->getTitle());
            *$tvShow->setSlug($slug); */

            /** @var UploadedFile $pictureFile */
            $pictureFile = $form->get('poster')->getData();
            if ($pictureFile) {
                $pictureFilename = $this->uploader->upload($pictureFile, 'poster');
                $tvShow->setPoster($pictureFilename);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($tvShow);
            $manager->flush();
            $this->addFlash("success", "La série a bien été ajoutée");
            return $this->redirectToRoute('tv_show_list', ["id" => $tvShow->getId()]);
        }

        return $this->render('tv_show/add.html.twig', 
        [
            'tvShowForm' => $form->createView()
        ]);

    }

    /**
     * @Route("/{id}/update", name="tv_show_update")
     */

     public function update(TvShow $tvShow, Request $request)
     {
         $form = $this->createForm(TvShowType::class, $tvShow);
         $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            $this->addFlash("success", "La série a bien été mise à jour");
            return $this->redirectToRoute('tv_show_view', ["id" => $tvShow->getId()]);

         }
         return $this->render('tv_show/update.html.twig', 
         [
             'tvShowForm' => $form->createView(),
             'tvShow' => $tvShow
         ]);    

     }

    /**
     * @Route("/{id}/delete", name="tv_show_delete", requirements={"id"="\d+"})
     */

    public function delete(TvShow $tvShow)
    {
           $manager = $this->getDoctrine()->getManager();
           $manager->remove($tvShow);
           $manager->flush();
           $this->addFlash("success", "La série a bien été supprimée");
           return $this->redirectToRoute('tv_show_list');
    }    
}
