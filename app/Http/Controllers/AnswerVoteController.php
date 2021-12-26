<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Requests\VoteRequest;
use App\Models\Answer;
use Illuminate\Http\Response;

class AnswerVoteController extends Controller
{
    use ApiHelpers;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Vote an answer
     *
     * @param Answer $answer
     * @param VoteRequest $request
     * @return Response
     */
    public function __invoke(Answer $answer, VoteRequest $request): Response
    {
        $fields = $request->validated();
        $vote = (int)$fields['vote'];
        auth()->user()->voteAnswer($answer, $vote);
        return $this->onSuccess([
            'votes_count' => $answer->votes_count
        ], "your vote recorded.");
    }
}
