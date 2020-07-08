<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_question_receives_a_new_answer_by_other_people()
    {
        $question = create(Question::class, [
            'user_id' => auth()->id()
        ]);

        $question->subscribe(auth()->id());

        $this->assertCount(0, auth()->user()->notifications);

        $question->addAnswer([
            'user_id' => auth()->id(),
            'content' => 'Some reply here'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $question->addAnswer([
            'user_id' => create(User::class)->id,
            'content' => 'Some reply here'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }
//
//    /** @test */
//    public function a_user_can_fetch_their_unread_notifications()
//    {
//        create(DatabaseNotification::class);
//
//        $response =  $this->getJson(route('user-notifications.index', ['user' => auth()->user()]))->json();
//
//        $this->assertCount(1, $response);
//    }
//
//    /** @test */
//    public function a_user_can_clear_a_notification()
//    {
//        $question = create(Question::class)->subscribe();
//
//        $question->addAnswer([
//            'user_id' => create(User::class)->id,
//            'content' => 'Some reply here'
//        ]);
//
//        $user = auth()->user();
//
//        $this->assertCount(1, $user->unreadNotifications);
//
//        $notification = $user->unreadNotifications->first();
//
//        $this->delete(route('user-notifications.destroy', [
//            'user' => $user,
//            'notification' => $notification
//        ]));
//
//        $this->assertCount(0, auth()->user()->fresh()->unreadNotifications);
//    }
}
