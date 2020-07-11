<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentCommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_comment_a_comment()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $comment = create(Comment::class);

        $this->post(route('comment-comments.store', ['comment' => $comment]), [
            'content' => 'This is a comment.'
        ]);
    }

    /** @test */
    public function signed_in_user_can_comment_a_comment()
    {
        $comment = create(Comment::class);
        $this->signIn($user = create(User::class));

        $response = $this->post(route('comment-comments.store', ['comment' => $comment]), [
            'content' => 'This is a reply.'
        ]);

        $response->assertStatus(302);

        $commentedComment = $comment->comments()->where('user_id', $user->id)->first();

        $this->assertNotNull($commentedComment);

        $this->assertEquals(1, $comment->comments()->count());
    }

    /** @test */
    public function content_is_required_to_comment_a_comment()
    {
        $this->withExceptionHandling();

        $comment = create(Comment::class);

        $this->signIn();

        $response = $this->post(route('comment-comments.store', ['comment' => $comment]), [
            'content' => null
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('content');
    }
}
