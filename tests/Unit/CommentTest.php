<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

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
}
