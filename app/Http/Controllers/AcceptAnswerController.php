<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Models\Answer;
use Illuminate\Http\Response;

class AcceptAnswerController extends Controller
{
    use ApiHelpers;

    /**
     * @param Answer $answer
     * @return Response
     */
    public function __invoke(Answer $answer): Response
    {
        $answer->question->acceptAnswer($answer);
        return $this->onSuccess($answer->id, "answer accepted as the best answer.");
    }
}
