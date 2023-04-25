<?php

namespace App\Service;

class Slugify 
{
    public function slug(string $word){
        $word = str_replace(' ', '-', $word);
        $word = str_replace('  ', '-', $word);
        $word = str_replace('@', '-', $word);
        $word = str_replace('<', '-', $word);
        return $word;
    }
}