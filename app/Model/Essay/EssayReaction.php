<?php

namespace App\Model\Essay;

use Illuminate\Database\Eloquent\Model;

class EssayReaction extends Model
{
    protected $fillable = ['id', 'essay_response_id', 'account_id'];
    public $timestamps = false;
}
