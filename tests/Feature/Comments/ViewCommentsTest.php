<?php

namespace Tests\Feature\Comments;

use App\Models\Answer;
use App\Models\Comment;
use App\Models\Question;
use Tests\Testcase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewCommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_request_all_comments_for_a_given_question()
    {
        $question = create(Question::class);
        create(Comment::class, [
            'commented_id' => $question->id,
            'commented_type' => Question::class
        ], 40);

        $response = $this->getJson(route('question-comments.index', ['question' => $question]))->json();

        $this->assertCount(10, $response['data']);
        $this->assertEquals(40, $response['total']);
    }

    /** @test */
    public function can_request_all_comments_for_a_given_answer()
    {
        $answer = create(Answer::class);
        create(Comment::class, [
            'commented_id' => $answer->id,
            'commented_type' => Answer::class
        ], 40);

        $response = $this->getJson(route('answer-comments.index', ['answer' => $answer]))->json();

        $this->assertCount(10, $response['data']);
        $this->assertEquals(40, $response['total']);
    }

    /** @test */
    public function can_request_all_comments_for_a_given_comment()
    {
        $comment = create(Comment::class);
        create(Comment::class, [
            'commented_id' => $comment->id,
            'commented_type' => Comment::class
        ], 40);

        $response = $this->getJson(route('comment-comments.index', ['comment' => $comment]))->json();

        $this->assertCount(10, $response['data']);
        $this->assertEquals(40, $response['total']);
    }
}
