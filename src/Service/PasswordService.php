<?php

namespace App\Service;

class PasswordService
{

    public function generatePassword(): string
    {
        $randomNumber = rand(4, 5);
        $bytes = random_bytes($randomNumber); // chaine de caractère alphanumérique, programme va piocher dedans 6 caractères au hasard. 
        // $caracteresSpeciaux = "! @ # $ % ^ & * ( ) _ - = + [ ] { } | \ : ; \" ' , < > . ?";
        $caracteresSpeciaux = "!@#$%^&*()_-=+[]{}|\:;\"',<>.?";
        $carSpeciauxArray = str_split($caracteresSpeciaux);
        $carSpecChosen = array_rand($carSpeciauxArray, rand(2, 4));

        $indexForArray = []; // Récupère les index choisi. 
        foreach ($carSpecChosen as  $index) {
            // dd($index);
            $indexForArray[] = $index;
        }
        $valueOfIndex = [];
        foreach ($indexForArray as $oneIndex) {
            $valueOfIndex[] = $carSpeciauxArray[$oneIndex];
        }
        $valueOfIndexToString = implode($valueOfIndex);
        // dd($valueOfIndexToString);
        $passedByBin = bin2hex($bytes);
        $passedByBinExploded = str_split($passedByBin);
        $mixedUpBin = [];
        foreach ($passedByBinExploded as $oneBin) {
            if (ctype_alpha($oneBin)) {
                $randedNumber = rand(1, 2);
                if ($randedNumber == 1) {
                    $oneBin = strtoupper($oneBin);
                } else if ($randedNumber == 2) {
                    $oneBin = strtolower($oneBin);
                }
                $mixedUpBin[] = $oneBin;
            } else {
                $mixedUpBin[] = $oneBin;
            }
        }
        $implodedMixedUp = implode($mixedUpBin);
        $generatedPassword = $implodedMixedUp . $valueOfIndexToString;
        $shuffleString = str_shuffle($generatedPassword);
        // dd($shuffleString);
        return $shuffleString;
    }
}
