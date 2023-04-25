<?php

namespace App\Service;

class TTC
{
    public function calcultva(int $montant, int $tva)
    {
        $ttc = $montant + $tva;
        return $ttc;
    }
}