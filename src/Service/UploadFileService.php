<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

class UploadFileService
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function uploadFile(
        $fileToUpload,
        // ?string $directoryPath,
        // ?string $pathDirectory,
        // ?string $logoMarque
    ) {
        $originalFilename = pathinfo(
            $fileToUpload->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $safeFileName = $this->slugger->slug($originalFilename);
        $newFileName = $safeFileName . '-' . uniqid() . '.' . $fileToUpload->guessExtension();
        // $fileToUpload->move($pathDirectory, $newFileName);
        // $marque->setLogo($newFileName);
        // $marqueFilePath =  $pathDirectory . $logoMarque;
        // dd($marqueFilePath);
        // if ($logoMarque) {
        //     unlink($marqueFilePath);
        // }
        return $newFileName;
    }

    public function removeFile($filePath)
    {
        unlink($filePath);
        if (!file_exists($filePath)) {
            return "Bien effacé";
        } else {
            return "Erreur lors de la suppression";
        };
    }
}
