<?php

namespace App\Http\Controllers;

use App\Events\PublishQuestion;
use App\Models\Question;
use Illuminate\Http\Request;

class PublishedQuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Question $question)
    {
        $this->authorize('update', $question);

        $question->publish();

        event(new PublishQuestion($question));

        return redirect("/questions/{$question->id}");
    }
}
