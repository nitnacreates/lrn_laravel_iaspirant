<?php

namespace App\Model\Theory;

use Illuminate\Database\Eloquent\Model;

class TheorySummary extends Model
{
    protected $fillable = ['id' ,'theory_id', 'account_id', 'user_type', 'locked', 'theory_summary'];
}
