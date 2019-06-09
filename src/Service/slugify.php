<?php


namespace App\Service;



class slugify
{
    public function generate(string $input): string
    {

        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $input);
        $slug = mb_strtolower(preg_replace( '/[^a-zA-Z0-9\-\s]/', '', $slug ));
        $slug = str_replace(' ','-',trim($slug));
        $slug = preg_replace('/([-])\\1+/', '$1', $slug);
        return $slug;
    }
}





