<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show','index']);

        $this->middleware('must-verify-email')->except(['index', 'show']);
    }

    public function index()
    {

    }

    public function store()
    {
        $this->validate(request(), [
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $question = Question::create([
            'user_id' => auth()->id(),
            'category_id' => request('category_id'),
            'title' => request('title'),
            'content' => request('content')
        ]);

        return redirect("/$question/$question->id");
    }

    public function show($questionId)
    {
        $question = Question::published()->findOrFail($questionId);

        $answers = $question->answers()->paginate(20);

        array_map(function (&$item) {
            return $this->appendVotedAttribute($item);
        }, $answers->items());

        return view('questions.show', [
            'question' => $question,
            'answers' => $answers
        ]);
    }
}
