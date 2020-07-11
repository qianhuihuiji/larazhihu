<?php

namespace Tests\Unit;

use App\Jobs\TranslateSlug;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use App\Models\Subscription;
use App\Notifications\QuestionWasUpdated;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function can_get_comments_count_attribute()
    {
        $question = create(Question::class);

        $question->comment('it is content', create(User::class));

        $this->assertEquals(1, $question->refresh()->commentsCount);
    }
}
