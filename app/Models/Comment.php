<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use \App\Models\Traits\CommentTrait;

    protected $guarded = ['id'];
}
