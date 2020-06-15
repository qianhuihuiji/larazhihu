<?php

namespace Tests\Feature;

use App\Answer;
use App\Question;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewQuestionsTest extends TestCase
{
    use RefreshDatabase;

    public function user_can_view_questions()
    {
        // 0. 抛出异常
        $this->withoutExceptionHandling();
        // 1. 访问链接 questions
        $test = $this->get('/questions');

        // 2. 正常返回 200
        $test->assertStatus(200);
    }

    /** @test */
    public function user_can_view_a_published_question()
    {
        $question = factory(Question::class)->create(['published_at' => Carbon::parse('-1 week')]);

        $this->get('/questions/' . $question->id)
            ->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($question->content);
    }

    /** @test */
    public function user_cannot_view_unpublished_question()
    {
        $question = factory(Question::class)->create(['published_at' => null]);

        $this->withExceptionHandling()->get('/questions/' . $question->id)
            ->assertStatus(404);
    }

    /** @test */
    public function can_see_answers_when_view_a_published_question()
    {
        $question = factory(Question::class)->state('published')->create();
        create(Answer::class, ['question_id' => $question->id], 40);

        $response = $this->get('/questions/' . $question->id);

        $result = $response->data('answers')->toArray();

        $this->assertCount(20, $result['data']);
        $this->assertEquals(40, $result['total']);
    }
}
