<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Comment;

class CommentCommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Comment $comment)
    {
        $this->validate(request(), [
            'content' => 'required'
        ]);

        $comment =  $comment->comment(request('content'), auth()->user());

        return back();
    }
}
