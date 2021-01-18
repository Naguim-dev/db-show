<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\TvShow;
use App\Form\PersonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    /**
     * @Route("/person/add", name="person_add")
     */
    public function add(Request $request)
    {
        $person = new Person();
        $personForm = $this->createForm(PersonType::class, $person);

        $personForm->handleRequest($request);
        if($personForm->isSubmitted() && $personForm->isValid())
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($person);
            $manager->flush();

            $this->addFlash("success", "L'acteur/réalisateur a bien été ajouté");
            return $this->redirectToRoute("tv_show_list");

        }
        
        return $this->render(
            "person/add.html.twig",
        [
            "personForm" => $personForm->createView(),
            "person" => $person
        ]);
    }

    /**
     * @Route("/person/{id}/update", name="person_update", requirements={"id"="\d+"})
     */
    public function update(Person $person, Request $request)
    {
        $personForm = $this->createForm(PersonType::class, $person);

        $personForm->handleRequest($request);
        if($personForm->isSubmitted() && $personForm->isValid())
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash("success", "L'acteur/réalisateur a bien été modifié");
            return $this->redirectToRoute("tv_show_list");

        }
        
        return $this->render(
            "person/update.html.twig",
        [
            "personForm" => $personForm->createView(),
            "person" => $person
        ]);
    }  
    
    /**
     * @Route("/person/{id}/delete", name="person_delete", requirements={"id"="\d+"})
     */

    public function delete(Person $person)
    {
           $manager = $this->getDoctrine()->getManager();
           $manager->remove($person);
           $manager->flush();
           $this->addFlash("success", "Le personnage a bien été supprimée");
           return $this->redirectToRoute('tv_show_list');
    } 
}
