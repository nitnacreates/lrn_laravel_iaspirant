<?php

namespace App\Facades;

class Subject
{
    public function checkSubTitle($sub)
    {
        $sub_arr = preg_match("/^o\-/i", trim($sub)) > 0 ? config('aspirant.optional_map') : config('aspirant.subject_map');
        return array_key_exists($sub, $sub_arr) ? $sub_arr[$sub] : 0;
    }
}
