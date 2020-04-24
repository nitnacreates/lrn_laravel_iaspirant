<?php

namespace App\Facades;

use Request; // use facade alias, or use Illuminate\Support\Facades\Request;

class SuperHelper
{
    public function getAccountId()
    {
        return Request::session()->get('account_id'); 
    }
}
