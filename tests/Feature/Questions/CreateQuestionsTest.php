<?php

namespace Tests\Feature\Questions;

use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_create_questions()
    {
        $this->withExceptionHandling();

        $this->post('/questions', [])
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_questions()
    {
        $this->signIn();

        $question = make(Question::class);

        $this->assertCount(0, Question::all());

        $this->post('/questions', $question->toArray());

        $this->assertCount(1, Question::all());
    }

    /** @test */
    public function title_is_required()
    {
        $this->signIn()->withExceptionHandling();

        $question = make(Question::class, ['title' => null]);

        $response =$this->post('/questions', $question->toArray());

        $response->assertRedirect();
        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function content_is_required()
    {
        $this->signIn()->withExceptionHandling();

        $question = make(Question::class, ['content' => null]);

        $response =$this->post('/questions', $question->toArray());

        $response->assertRedirect();
        $response->assertSessionHasErrors('content');
    }

    /** @test */
    public function category_id_is_required()
    {
        $this->signIn()->withExceptionHandling();

        $question = make(Question::class, ['category_id' => null]);

        $response =$this->post('/questions', $question->toArray());

        $response->assertRedirect();
        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function category_id_is_existed()
    {
        $this->signIn()->withExceptionHandling();

        create(Category::class, ['id' => 1]);

        $question = make(Question::class, ['category_id' => 999]);

        $response =$this->post('/questions', $question->toArray());

        $response->assertRedirect();
        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function authenticated_users_must_confirm_email_address_before_creating_questions()
    {
        $this->signIn(create(User::class, ['email_verified_at' => null]));

        $question = make(Question::class);

        $this->post('/questions', $question->toArray())
            ->assertRedirect(route('verification.notice'));
    }

    /** @test */
    public function get_slug_when_create_a_question()
    {
        $this->signIn();

        $question = make(Question::class, ['title' => '英语 英语']);

        $this->post('/questions', $question->toArray());

        $storedQuestion = Question::first();

        $this->assertEquals('english-english', $storedQuestion->slug);
    }
}
