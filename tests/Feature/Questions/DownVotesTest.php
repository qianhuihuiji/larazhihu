<?php

namespace Tests\Feature\Questions;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\Feature\VoteDownContractTest;
use Tests\Testcase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DownVotesTest extends TestCase
{
    use RefreshDatabase;
    use VoteDownContractTest;

    protected function getVoteDownUri($answer = null)
    {
        return $answer ? "/answers/{$answer->id}/down-votes" : '/answers/1/up-votes';
    }

    protected function getModel()
    {
        return Answer::class;
    }

    protected function downVotes($answer)
    {
        return $answer->refresh()->votes('vote_down')->get();
    }
}
