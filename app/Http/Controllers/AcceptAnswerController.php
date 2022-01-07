<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Answer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

class AcceptAnswerController extends Controller
{
    use ApiHelpers;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @param Answer $answer
     * @return Response
     * @throws AuthorizationException
     */
    public function __invoke(Answer $answer): Response
    {
        $this->authorize('accept', $answer);
        $answer->question->acceptAnswer($answer);
        return $this->onSuccess(['accepted_answer_id'=>$answer->id], "answer accepted as the best answer.");
    }
}
