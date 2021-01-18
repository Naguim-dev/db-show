<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    private $uploadedFileDirectory;


    public function __construct($uploadedFileDirectory)
    {
        $this->uploadedFileDirectory = $uploadedFileDirectory;
    }
    // Méthode pour l'upload de fichiers (images, document pdf)
    public function upload(UploadedFile $file, $subFolder)
    {
        $pictureFilename = uniqid() . "." .$file->guessExtension();
        
   
        $file->move(
            $this->uploadedFileDirectory . DIRECTORY_SEPARATOR . $subFolder,
            $pictureFilename
        );

        return $pictureFilename;
    }

}