<?php

namespace App\Model\Comment;

use Illuminate\Database\Eloquent\Model;

class CommentReaction extends Model
{
    protected $fillable = ['comment_id', 'account_id'];
    public $timestamps = false;
}
