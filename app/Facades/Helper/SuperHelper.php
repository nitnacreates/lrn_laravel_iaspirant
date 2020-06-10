<?php

namespace App\Facades\Helper;

use Request; // use facade alias, or use Illuminate\Support\Facades\Request;

class SuperHelper
{
    public function getAccountId()
    {
        return Request::session()->get('account_id'); 
    }

    public function transformDate($from_format, $to_format, $date_input)
    {
        $dt = \DateTime::createFromFormat($from_format, $date_input);
        return $dt->format($to_format);
    }
}
