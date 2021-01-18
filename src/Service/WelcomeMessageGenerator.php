<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class WelcomeMessageGenerator
{

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }



    public function getRandomMessage()
    {
        $anonymousMessages = [
            "coucou anonymous",
            "salut anonymous",
            "hi anonymous"
        ];

        $userMessages = [
            "Bienvenue #name",
            "Re-bonjour #name"
        ];

        $user = $this->tokenStorage->getToken()->getUser();
        $message = null;

        if($user instanceof User){

            $message = $userMessages[mt_rand(0, count($userMessages) - 1)];
            $message = str_replace("#name", $user->getUsername(), $message);
        }

        else {

            $message = $anonymousMessages[mt_rand(0, count($anonymousMessages) - 1)];
        }

        
        return $message;

    }
}