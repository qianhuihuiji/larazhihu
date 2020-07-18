<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use \App\Models\Traits\VoteTrait;
    use \App\Models\Traits\InvitedUsersTrait;

    protected $guarded = ['id'];

    protected $with = ['owner'];

    protected $appends = [
        'upVotesCount',
        'downVotesCount',
    ];

    public function commented()
    {
        return $this->morphTo();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
