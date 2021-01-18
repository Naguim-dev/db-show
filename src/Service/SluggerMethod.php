<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

class SluggerMethod
{

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }


    public function sluggifyText($str)
    {
        return $this->slugger->slug($str)->lower();
    }

}