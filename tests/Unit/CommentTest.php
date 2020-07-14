<?php

namespace Tests\Unit;

use App\Events\PostComment;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    use AddCommentContractTest;

    public function getCommentModel()
    {
        return create(Comment::class);
    }

    /** @test */
    public function can_comment_a_comment()
    {
        $comment = create(Comment::class);

        $comment->comment('it is content', create(User::class));

        $this->assertEquals(1, $comment->refresh()->comments()->count());
    }

    /** @test */
    public function a_comment_has_many_comments()
    {
        $comment = create(Comment::class);

        create(Comment::class, [
            'commented_id' => $comment->id,
            'commented_type' => $comment->getMorphClass(),
            'content' => 'it is a comment'
        ]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\MorphMany', $comment->comments());
    }

    /** @test */
    public function can_get_comments_count_attribute()
    {
        $comment = create(Comment::class);

        $comment->comment('it is content', create(User::class));

        $this->assertEquals(1, $comment->refresh()->commentsCount);
    }

    /** @test */
    public function an_event_is_dispatched_when_a_comment_is_added()
    {
        Event::fake();

        $user = create(User::class);

        $comment = create(Comment::class);

        $comment->comment('it is a content', $user);

        Event::assertDispatched(PostComment::class);
    }

    /** @test */
    public function a_comment_has_morph_to_attribute()
    {
        $comment = create(Comment::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\MorphTo', $comment->commented());
    }

    /** @test */
    public function a_comment_belongs_to_an_owner()
    {
        $comment = create(Comment::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $comment->owner());
        $this->assertInstanceOf('App\Models\User', $comment->owner);
    }
}
