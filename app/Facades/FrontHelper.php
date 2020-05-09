<?php

namespace App\Facades;
use Illuminate\Support\Facades\Auth;

use Request; // use facade alias, or use Illuminate\Support\Facades\Request;

class FrontHelper
{
    public function checkAuthId($user_id)
    {
        return Auth::id() == $user_id;
    }

    public function transformDate($from_format, $to_format, $date_input)
    {
        $dt = \DateTime::createFromFormat($from_format, $date_input);
        return $dt->format($to_format);
    }
}
