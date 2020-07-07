<?php

namespace Tests\Feature\Questions;

use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribeQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_subscribe_to_or_unsubscribe_from_questions()
    {
        $this->withExceptionHandling();

        $question = create(Question::class);

        $this->post($question->path() . '/subscriptions')
            ->assertRedirect('/login');

        $this->delete($question->path() . '/subscriptions')
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_subscribe_to_questions()
    {
        $this->signIn();

        $question = create(Question::class);

        $this->post($question->path() . '/subscriptions');

        $this->assertCount(1, $question->subscriptions);
//
//        $question->addAnswer([
//            'user_id' => auth()->id(),
//            'content' => 'This is a content'
//        ]);
//
//        $this->assertCount(1, auth()->user()->refresh()->notifications);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_questions()
    {
        $this->signIn();

        $question = create(Question::class);

        $this->post($question->path() . '/subscriptions');
        $this->delete($question->path() . '/subscriptions');

        $this->assertCount(0, $question->subscriptions);
    }
}
