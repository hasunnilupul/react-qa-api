<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Requests\VoteRequest;
use App\Models\Question;
use Illuminate\Http\Response;

class QuestionVoteController extends Controller
{
    use ApiHelpers;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Vote a question
     *
     * @param Question $question
     * @param VoteRequest $request
     * @return Response
     */
    public function __invoke(Question $question, VoteRequest $request): Response
    {
        $fields = $request->validated();
        $vote = (int)$fields['vote'];
        $votes_count = auth()->user()->voteQuestion($question, $vote);
        return $this->onSuccess([
            'votes_count' => $votes_count
        ], "your vote recorded.");
    }
}
