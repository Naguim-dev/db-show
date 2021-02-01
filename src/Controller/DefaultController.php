<?php

namespace App\Controller;

use App\Service\SluggerMethod;
use App\Service\WelcomeMessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class DefaultController extends AbstractController
{

    private $welcomeMessageGenerator;
    private $sluggerMethod;

    public function __construct(WelcomeMessageGenerator $welcomeMessageGenerator, SluggerMethod $sluggerMethod)
    {
        $this->welcomeMessageGenerator = $welcomeMessageGenerator;
        $this->sluggerMethod = $sluggerMethod;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {


        /* $welcomeMessage = $this->welcomeMessageGenerator->getRandomMessage();
        $str = "Salut à toi !";
        $slugText = $this->sluggerMethod->sluggifyText($str);
        */
        return $this->redirectToRoute('tv_show_list');

        return $this->render(
            'default/homepage.html.twig',
            /* [
                "welcomeMessage" => $welcomeMessage,
                "slugText" => $slugText
            ]
            */
        );
    }
}
