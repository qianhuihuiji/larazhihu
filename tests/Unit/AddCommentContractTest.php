<?php

namespace Tests\Unit;

use App\Models\User;
use App\Notifications\YouWereMentionedInComment;
use Illuminate\Support\Facades\Notification;

trait AddCommentContractTest
{
    /** @test */
    public function an_notification_is_sent_when_a_comment_is_added()
    {
        Notification::fake();

        $john = create(User::class, [
            'name' => 'John'
        ]);

        $model = $this->getCommentModel();

        $model->comment("@John Thank you", $john);

        Notification::assertSentTo($john, YouWereMentionedInComment::class);
    }

    abstract protected function getCommentModel();
}
