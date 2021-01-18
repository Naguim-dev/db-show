<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\TvShow;
use App\Form\SeasonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeasonController extends AbstractController
{
    /**
     * @Route("/season/add/{id}", name="season_add", requirements={"id"="\d+"})
     */
    public function add(TvShow $tvShow, Request $request)
    {
        $season = new Season();
        $season->setTvShow($tvShow);
        $seasonForm = $this->createForm(SeasonType::class, $season);

        $seasonForm->handleRequest($request);
        if($seasonForm->isSubmitted() && $seasonForm->isValid())
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($season);
            $manager->flush();

            $this->addFlash("success", "Le saison a bien été ajoutée");
            return $this->redirectToRoute("tv_show_view", ["id" =>$tvShow->getId()]);

        }
        
        return $this->render(
            "season/add.html.twig",
        [
            "seasonForm" => $seasonForm->createView(),
            "season" => $season
        ]);
    }

    /**
     * @Route("/season/{id}/update", name="season_update", requirements={"id"="\d+"})
     */
    public function update(Season $season, Request $request)
    {
        $seasonForm = $this->createForm(SeasonType::class, $season);

        $seasonForm->handleRequest($request);
        if($seasonForm->isSubmitted() && $seasonForm->isValid())
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash("success", "Le saison a bien été modifiée");
            return $this->redirectToRoute("tv_show_view", ["id" => $season->getTvShow()->getId()]);

        }
        
        return $this->render(
            "season/update.html.twig",
        [
            "seasonForm" => $seasonForm->createView(),
            "season" => $season
        ]);
    }

            /**
     * @Route("/season/{id}/delete", name="season_delete", requirements={"id"="\d+"})
     */

    public function delete(Season $season)
    {
           $manager = $this->getDoctrine()->getManager();
           $manager->remove($season);
           $manager->flush();
           $this->addFlash("success", "La saison a bien été supprimée");
           return $this->redirectToRoute('tv_show_view', ["id" =>$season->getTvShow()->getId()]);
    }  
    


}
