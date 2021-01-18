<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\TvShow;
use App\Form\CharacterType;
use App\Service\Uploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
    private $uploader;

    public function __construct(Uploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @Route("/character/add/{id}", name="character_add", requirements={"id"="\d+"})
     */
    public function add(TvShow $tvShow, Request $request)
    {
        $character = new Character();
        $character->setTvShow($tvShow);
        $characterForm = $this->createForm(CharacterType::class, $character);

        $characterForm->handleRequest($request);
        if($characterForm->isSubmitted() && $characterForm->isValid())
        {
            /** @var UploadedFile $pictureFile */
            $pictureFile = $characterForm->get('picture')->getData();
            if ($pictureFile) {
                $pictureFilename = $this->uploader->upload($pictureFile, 'character');
                $character->setPictureFilename($pictureFilename);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($character);
            $manager->flush();

            $this->addFlash("success", "Le personnage a bien été ajouté");
            return $this->redirectToRoute("tv_show_view", ["id" =>$tvShow->getId()]);

        }
        
        return $this->render(
            "character/add.html.twig",
        [
            "characterForm" => $characterForm->createView()
        ]);
    }


        /**
     * @Route("/character/{id}/update", name="character_update", requirements={"id"="\d+"})
     */

    public function update(Character $character, Request $request)
    {
        $characterForm = $this->createForm(CharacterType::class, $character);
        $characterForm->handleRequest($request);
        if($characterForm->isSubmitted() && $characterForm->isValid()){

               /** @var UploadedFile $pictureFile */
               $pictureFile = $characterForm->get('picture')->getData();
               if ($pictureFile) {
                $pictureFilename = $this->uploader->upload($pictureFile, 'character');
                $character->setPictureFilename($pictureFilename);
               }
   
           $manager = $this->getDoctrine()->getManager();
           $manager->flush();
           $this->addFlash("success", "Le personnage bien été mise à jour");
           return $this->redirectToRoute('tv_show_view', ["id" => $character->getTvShow()->getId()]);

        }
        return $this->render('character/update.html.twig', 
        [
            'characterForm' => $characterForm->createView(),
            'character' => $character
        ]);    

    }  

            /**
     * @Route("/character/{id}/delete", name="character_delete", requirements={"id"="\d+"})
     */

    public function delete(Character $character)
    {
           $manager = $this->getDoctrine()->getManager();
           $manager->remove($character);
           $manager->flush();
           $this->addFlash("success", "Le personnage a bien été supprimée");
           return $this->redirectToRoute('tv_show_view', ["id" =>$character->getTvShow()->getId()]);
    }  
    
    


}
