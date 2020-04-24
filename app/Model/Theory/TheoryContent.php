<?php

namespace App\Model\Theory;

use Illuminate\Database\Eloquent\Model;

class TheoryContent extends Model
{
    protected $primaryKey = 'theory_id';
    public $timestamps = false;
    protected $fillable = ['theory_id', 'theory_content', 'theory_reference'];
}
