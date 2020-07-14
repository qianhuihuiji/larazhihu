<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class CommentCommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index(Comment $comment)
    {
        $comments = $comment->comments()->paginate(10);

        array_map(function (&$item) {
            return $this->appendVotedAttribute($item);
        }, $comments->items());

        return $comments;
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
